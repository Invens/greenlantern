#!/bin/bash
set -e  # Stop script on first error

# Color variables
GRY='\033[1;30m'
RED='\033[0;31m'
BLU='\033[0;34m'
GRN='\033[0;32m'
PUL='\033[0;35m'
RST='\033[0m'

# ✅ Check if running as root
check_root() {
    if [ "$(id -u)" -ne 0 ]; then
        printf "${RED}Please run as root!${RST}\n"
        exit 1
    fi
}

# ✅ Detect OS and Package Manager
detect_os() {
    KERNEL="$(uname -s | tr '[:upper:]' '[:lower:]')"
    if [ -f "/etc/os-release" ]; then
        DISTRO=$(grep ^ID= /etc/os-release | cut -d= -f2 | tr -d '"')
    else
        printf "${RED}Unknown OS. Install dependencies manually.${RST}\n"
        exit 1
    fi
}

# ✅ Install dependencies based on OS
install_dependencies() {
    printf "${GRN}Installing dependencies...${RST}\n"

    case "$DISTRO" in
        debian|ubuntu|kali|linuxmint|parrot)
            apt-get update
            apt-get install -y python3 python3-pip php curl unzip
        ;;
        arch|manjaro|arcolinux|garuda|artix)
            pacman -Sy --noconfirm python python-pip php curl unzip
        ;;
        fedora|centos|rhel)
            yum update -y
            yum install -y python3 python3-pip php curl unzip
        ;;
        termux)
            pkg update
            pkg install -y python php curl unzip
        ;;
        darwin)
            brew update
            brew install python php curl unzip
        ;;
        freebsd|openbsd)
            pkg update
            pkg install -y python3 py3-pip php curl unzip
        ;;
        *)
            printf "${RED}Unsupported OS. Install dependencies manually.${RST}\n"
            exit 1
        ;;
    esac
}

# ✅ Install Cloudflare Tunnel
install_cloudflared() {
    if ! command -v cloudflared &>/dev/null; then
        printf "${GRN}Installing Cloudflare Tunnel...${RST}\n"
        curl -fsSL https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 -o /usr/local/bin/cloudflared
        chmod +x /usr/local/bin/cloudflared
    else
        printf "${BLU}Cloudflare Tunnel already installed.${RST}\n"
    fi
}

# ✅ Install Python requirements
install_python_requirements() {
    if [ -f "requirements.txt" ]; then
        python3 -m pip install --upgrade pip
        python3 -m pip install -r requirements.txt
    else
        printf "${RED}requirements.txt not found! Skipping Python dependencies.${RST}\n"
    fi
}

# ✅ Main function to run all installations
main() {
    check_root
    detect_os
    install_dependencies
    install_cloudflared
    install_python_requirements
    printf "${GRN}All dependencies installed successfully!${RST}\n"
}

main  # Run script
