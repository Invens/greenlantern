from colorama import Fore,Back,Style
import subprocess,json,time,hashlib

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



def run_php_server(port):
    print(f"Starting PHP server on port {port}...")
    subprocess.Popen(["php", "-S", f"localhost:{port}"])

    # Wait a few seconds to ensure PHP server starts
    time.sleep(2)

    # Start Cloudflare Tunnel
    print("Starting Cloudflare Tunnel...")
    tunnel_process = subprocess.Popen(["cloudflared", "tunnel", "--url", f"http://localhost:{port}"], stdout=subprocess.PIPE, stderr=subprocess.PIPE)

    # Print the Cloudflare tunnel URL
    for line in tunnel_process.stdout:
        decoded_line = line.decode().strip()
        print(decoded_line)
        if "https://" in decoded_line:
            print(f"Your public URL: {decoded_line}")
            break

