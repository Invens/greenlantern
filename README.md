<h1 align="center" style="font-size: 36px;">Green Lantern</h1>

<p align="center">
  <a href="https://github.com/Invens/greenlantern.git">
    <img src="https://github.com/Invens/greenlantern/blob/main/.imgs/greenlantern.png" alt="Green Lantern" width="300">
  </a>
</p>

<h4 align="center">A Powerful Tool with Next-Level Capabilities.</h4>

<p align="center">
  <a href="http://python.org">
    <img src="https://img.shields.io/badge/python-v3-blue">
  </a>
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/php-7.4.4-green" alt="php">
  </a>
  <a href="https://en.wikipedia.org/wiki/Linux">
    <img src="https://img.shields.io/badge/Platform-Linux-red">
  </a>
</p>

---

## 🚀 Features:

- ✅ **Obtain Device Information** (No Permission Required!)  
- ✅ **Access Device Location** (Smartphones)  
- ✅ **Access Webcam** 📷  
- ✅ **Access Microphone** 🎙️  
- ✅ **Remote Control Functionalities** 🖥️  

---

## 🛠 Update Log:

- 📅 **Latest Update**: March 2025  
- 🔧 **Rebuilt from Scratch** – Now a **Web Panel** (previously CLI-based).  
- ⚡ **Bug Fixes & Performance Optimization**  
- 🌐 **Auto-download & Setup for Ngrok**  
- 📂 **Downloadable Logs (NEW Feature!)**  
- 🎨 **Enhanced & Modernized UI**  

> ⚠ **Ngrok Removal Notice**:  
> Green Lantern now sets up a local server, but **you must manually start Ngrok** on your desired port.  

---

## 🔐 Default Credentials:

- **Username**: `admin`  
- **Password**: `admin`  
- Edit `config.php` to change credentials.  

---

## 📦 Dependencies

**Green Lantern** requires:

- `PHP`
- `Python 3`
- `Git`
- `Ngrok` (for tunneling)  

---

## 🖥️ Platforms Tested:

✔ **Kali Linux 2024**  
✔ **macOS Ventura / M1/M2**  
✔ **Termux (Android)**  
✔ **Personal Host (DirectAdmin & cPanel)**  

---

## 🔧 Installation on Kali Linux:

```bash
git clone https://github.com/Invens/greenlantern.git
cd greenlantern
sudo bash install.sh
sudo python3 -m pip install -r requirements.txt
sudo python3 st.py
