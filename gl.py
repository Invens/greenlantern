from modules import check
check.dependency()
check.check_started()
from colorama import Back, Fore, Style
from modules import banner, control
check.check_update()

try:
    while True:
        banner.banner()
        port = control.run_php_server()  # Runs PHP + Cloudflare Tunnel

        input(f" 🌐 Press Enter to Exit and Stop Services {Style.RESET_ALL}")
        control.stop_services()
        exit()

except KeyboardInterrupt:
    print("\n🛑 Detected CTRL+C, stopping services...")
    control.stop_services()
    exit()
