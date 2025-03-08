#!/bin/bash
set -e  # Stop execution if any command fails

# Color variables
GRY='\033[1;30m'
RED='\033[0;31m'
BLU='\033[0;34m'
GRN='\033[0;32m'
PUL='\033[0;35m'
RST='\033[0m'

# ✅ Function to check if running as root
check_root() {
    if [ "$(id -u)" -ne 0 ]; then
        printf "${RED}Please run as root!${RST}\n"
        exit 1
    fi
}

# ✅ Function to detect OS
detect_os() {
    KERNEL="$(uname -s | tr '[:upper:]' '[:lower:]')"
    
    if [ -f "/etc/os-release" ]; then
        DISTRO=$(grep ^ID= /etc/os-release | cut -d= -f2 | tr -d '"')
    else
        printf "${RED}Could not detect your Linux distribution. Install dependencies manually.${RST}\n"
        exit 1
    fi
}

# ✅ Function to install dependencies based on OS
install_dependencies() {
    printf "${GRN}Installing dependencies...${RST}\n"

    case "$DISTRO" in
        debian|ubuntu|kali|pop|linuxmint|parrot)
            apt-get update && apt-get install -y python3 python3-pip python3-venv php curl unzip
        ;;
        arch|manjaro|arcolinux|garuda|artix)
            pacman -Sy --noconfirm python python-pip php curl unzip
        ;;
        fedora|centos|rhel)
            yum update -y && yum install -y python3 python3-pip python3-virtualenv php curl unzip
        ;;
        termux)
            pkg update && pkg install -y python php curl unzip
        ;;
        alpine)
            apk add --no-cache python3 py3-pip py3-virtualenv php curl unzip
        ;;
        gentoo)
            emerge --sync && emerge -av dev-lang/php dev-python/pip net-misc/curl app-arch/unzip
        ;;
        freebsd|openbsd)
            pkg update && pkg install -y python3 py3-pip py3-virtualenv php curl unzip
        ;;
        *)
            printf "${RED}Unsupported OS. Please install dependencies manually.${RST}\n"
            exit 1
        ;;
    esac
}

# ✅ Function to set up a Python virtual environment
setup_venv() {
    if [ ! -d "venv" ]; then
        printf "${GRN}Creating a Python virtual environment...${RST}\n"
        python3 -m venv venv
    fi
    source venv/bin/activate
    printf "${BLU}Virtual environment activated.${RST}\n"
}

# ✅ Function to install Python dependencies inside venv
install_python_requirements() {
    setup_venv
    if [ -f "requirements.txt" ]; then
        pip install --upgrade pip
        pip install -r requirements.txt
    else
        printf "${RED}requirements.txt not found! Skipping Python dependencies.${RST}\n"
    fi
}

# ✅ Function to install Cloudflare Tunnel
install_cloudflared() {
    if ! command -v cloudflared &>/dev/null; then
        printf "${GRN}Installing Cloudflare Tunnel...${RST}\n"
        curl -fsSL https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 -o /usr/local/bin/cloudflared
        chmod +x /usr/local/bin/cloudflared
    else
        printf "${BLU}Cloudflare Tunnel is already installed.${RST}\n"
    fi
}

# ✅ Main function to run all installations
main() {
    check_root
    detect_os
    install_dependencies
    install_cloudflared
    install_python_requirements
    printf "${GRN}✅ Installation complete! Use 'source venv/bin/activate' before running the tool.${RST}\n"
}

main  # Run the script
