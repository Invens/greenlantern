from modules import check
check.dependency()
check.check_started()
from colorama import Back, Fore, Style
from modules import banner, control
check.check_update()

PORT = 2525

while True:
    banner.banner()
    control.run_php_server(PORT)  # This now starts PHP + Cloudflare Tunnel
    try:
        input(" "+Fore.WHITE+Back.RED+"Press Enter to Exit and Turn Off localhost "+Style.RESET_ALL)
        control.kill_php_proc()
        exit()
    
    except:
        control.kill_php_proc()
        exit()
