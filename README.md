<h1 align="center">
  <br>
  <a href="https://github.com/Invens/greenlantern.git">
    <img src="https://github.com/Invens/greenlantern/blob/main/.imgs/greenlantern.png" alt="Green Lantern" width="300">
  </a>
</h1>

<h4 align="center">A Powerful Tool with Next-Level Capabilities.</h4>

<p align="center">

  <a href="http://python.org">
    <img src="https://img.shields.io/badge/python-v3-blue">
  </a>
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/php-7.4.4-green"
         alt="php">
  </a>
  <a href="https://en.wikipedia.org/wiki/Linux">
    <img src="https://img.shields.io/badge/Platform-Linux-red">
  </a>

</p>

---

## 🚀 Features:

✅ **Obtain Device Information** (No Permission Required!)  
✅ **Access Device Location** (Smartphones)  
✅ **Access Webcam** 📷  
✅ **Access Microphone** 🎙️  
✅ **Remote Control Functionalities** 🖥️  

---

## 🛠 Update Log:

📅 **Latest Update**: March 2025  
🔧 **Completely Rebuilt from Scratch** – Now available as a **Web Panel** (previously CLI-based).  
⚡ **Bug Fixes & Performance Optimization**  
🌐 **Auto-download & Setup for Ngrok**  
📂 **Downloadable Logs (NEW Feature!)**  
🛠 **Clear Logs Anytime**  
📡 **Run it on Your Personal Host & Domain** – No more Ngrok limitations.  
🎨 **Enhanced & Modernized UI**  

> ⚠ **Ngrok Removal Notice**:  
> In the latest version, we have removed built-in Ngrok and provided users full control over local hosting. Now, Green Lantern sets up a local server, but **you must manually configure and start Ngrok** on your desired port.  
---

## 🔐 Default Credentials:

- **Username**: `admin`  
- **Password**: `admin`  
- You can edit `config.php` to change these settings.  

---

## 📦 Dependencies

**Green Lantern** requires the following programs to function properly:

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
