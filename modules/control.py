from colorama import Fore,Back,Style
import subprocess,json,time,hashlib
import socket 
import random
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



def find_free_port():
    """Find a free port dynamically."""
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.bind(("", 0))  # Bind to any available port
        return s.getsockname()[1]  # Get the assigned port number

def run_php_server(port=2525):
    """Start PHP server on a free port if 2525 is busy."""
    while True:
        try:
            # Check if port is in use
            with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                if s.connect_ex(("localhost", port)) == 0:
                    print(f"Port {port} is busy, trying another...")
                    port = random.randint(2000, 9999)  # Pick a random port
                else:
                    break  # Port is free

        except Exception:
            break  # If an error occurs, assume the port is free

    print(f"Starting PHP server on port {port}...")
    subprocess.Popen(["php", "-S", f"localhost:{port}"], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

    # Wait a few seconds to ensure PHP server starts
    time.sleep(2)

    # Start Cloudflare Tunnel
    print("Starting Cloudflare Tunnel...")
    tunnel_process = subprocess.Popen(["cloudflared", "tunnel", "--url", f"http://localhost:{port}"], stdout=subprocess.PIPE, stderr=subprocess.PIPE)

    # Capture and print the Cloudflare public URL
    for line in tunnel_process.stdout:
        decoded_line = line.decode().strip()
        if "https://" in decoded_line:
            print(f"Your public URL: {decoded_line}")
            break
