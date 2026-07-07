"""
Alur Sistem — Sistem Informasi Antrian Pasien Puskesmas Salem
=============================================================
Diagram Use Case v4:
- Pasien     -> Fitur Publik (memencar langsung)
- Admin      -> Fitur Publik (juga bisa, panah putus-putus)
- Admin      -> Login (wajib)
- Login      -> Fitur Petugas (memencar, bukan rantai linear)
- Logo Puskesmas Salem ditampilkan di pojok kiri atas diagram

Output: alur_sistem.png
"""

import numpy as np
from PIL import Image
import matplotlib
matplotlib.use("Agg")
import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
from matplotlib.patches import FancyBboxPatch
from matplotlib.offsetbox import OffsetImage, AnnotationBbox

# ─────────────────────────────────────────────
# Canvas
# ─────────────────────────────────────────────
fig, ax = plt.subplots(figsize=(17, 20))
ax.set_xlim(0, 17)
ax.set_ylim(0, 20)
ax.axis("off")
fig.patch.set_facecolor("#F7FAF8")

# ─────────────────────────────────────────────
# Colors
# ─────────────────────────────────────────────
C_SYS_BG   = "#EEF6EE"
C_SYS_BD   = "#2D6A4F"
C_TITLE    = "#1B4332"
C_UC_TEXT  = "#1A1A2E"
C_PASIEN   = "#1565C0"
C_ADMIN    = "#B71C1C"
C_LOGIN_BG = "#C8E6C9"
C_LOGIN_BD = "#2D6A4F"
C_PUB_BG   = "#EBF5FB"
C_PUB_BD   = "#1565C0"
C_ADM_BD   = "#555555"

# ─────────────────────────────────────────────
# Helpers
# ─────────────────────────────────────────────
def ellipse(ax, cx, cy, w, h, label,
            bg="#FFFFFF", bd="#555555", lw=1.4, fs=8.6):
    ax.add_patch(mpatches.Ellipse(
        (cx, cy), width=w, height=h,
        facecolor=bg, edgecolor=bd, linewidth=lw, zorder=3))
    ax.text(cx, cy, label, ha="center", va="center",
            fontsize=fs, fontweight="bold", color=C_UC_TEXT,
            multialignment="center", zorder=4)

def actor(ax, cx, cy, label, color):
    ax.add_patch(plt.Circle((cx, cy+0.65), 0.30, color=color, zorder=5))
    ax.plot([cx, cx],          [cy+0.35, cy-0.28], color=color, lw=2.2, zorder=5)
    ax.plot([cx-0.38, cx+0.38],[cy+0.10, cy+0.10], color=color, lw=2.2, zorder=5)
    ax.plot([cx, cx-0.38],     [cy-0.28, cy-0.82], color=color, lw=2.2, zorder=5)
    ax.plot([cx, cx+0.38],     [cy-0.28, cy-0.82], color=color, lw=2.2, zorder=5)
    ax.text(cx, cy-1.10, label, ha="center", va="top",
            fontsize=9.0, fontweight="bold", color=color, zorder=5)

def arrow(ax, x1, y1, x2, y2, color, lw=1.3,
          ls="solid", rad=0.0, style="->"):
    ax.annotate("", xy=(x2, y2), xytext=(x1, y1),
        arrowprops=dict(arrowstyle=style, color=color, lw=lw,
                        linestyle=ls,
                        connectionstyle=f"arc3,rad={rad}"),
        zorder=2)

# ─────────────────────────────────────────────
# Logo helper  — hapus background putih → transparan
# ─────────────────────────────────────────────
def load_logo_transparent(path, tolerance=30):
    """Baca logo, ganti piksel putih (±tolerance) jadi transparan."""
    img = Image.open(path).convert("RGBA")
    data = np.array(img, dtype=np.uint8)
    r, g, b, a = data[...,0], data[...,1], data[...,2], data[...,3]
    white_mask = (r > 255 - tolerance) & (g > 255 - tolerance) & (b > 255 - tolerance)
    data[white_mask, 3] = 0          # set alpha=0 untuk piksel putih
    return Image.fromarray(data, "RGBA")

LOGO_PATH = r"E:\freelance\silla\assets\logo.png"
logo_img  = load_logo_transparent(LOGO_PATH)
logo_arr  = np.array(logo_img)

# ─────────────────────────────────────────────
# System Boundary
# ─────────────────────────────────────────────
ax.add_patch(FancyBboxPatch(
    (3.6, 0.5), 12.8, 19.0,
    boxstyle="round,pad=0.18",
    facecolor=C_SYS_BG, edgecolor=C_SYS_BD,
    linewidth=2.2, zorder=1))

# Embed logo di pojok kiri atas system boundary
logo_ob = OffsetImage(logo_arr, zoom=0.13)   # zoom dikecilkan agar proporsional
logo_ab = AnnotationBbox(
    logo_ob,
    (4.55, 19.2),                # posisi (x, y) dalam data-units
    frameon=False,
    zorder=6
)
ax.add_artist(logo_ab)

# Judul di sebelah kanan logo (geser kanan sedikit)
ax.text(10.8, 19.35,
        "Sistem Informasi Antrian Pasien Puskesmas Salem",
        ha="center", va="center",
        fontsize=11.5, fontweight="bold", color=C_TITLE, zorder=5)

# ─────────────────────────────────────────────
# PUBLIC Use-Cases   (zona atas — kolom kanan)
# ─────────────────────────────────────────────
# Sebar X sedikit agar panah terlihat memencar
pub_ucs = [
    (9.2,  18.0, "Daftar Antrian\n(Kiosk Online)"),
    (11.6, 16.5, "Cek Status\nAntrian"),
    (9.2,  15.1, "Lihat Jadwal\nDokter"),
    (11.6, 13.7, "Monitor Display\nAntrian"),
]
for cx, cy, lbl in pub_ucs:
    ellipse(ax, cx, cy, 3.1, 0.90, lbl, bg=C_PUB_BG, bd=C_PUB_BD)

# ─────────────────────────────────────────────
# Divider
# ─────────────────────────────────────────────
ax.plot([3.8, 16.2], [12.85, 12.85],
        color="#AAAAAA", lw=0.9, ls="--", zorder=2)
ax.text(10.0, 12.98,
        "Fitur Publik (tanpa login)  |  Fitur Petugas (setelah login)",
        ha="center", va="bottom",
        fontsize=7.2, color="#777777", style="italic", zorder=5)

# ─────────────────────────────────────────────
# LOGIN node   (di tengah, tepat di garis batas)
# ─────────────────────────────────────────────
LX, LY = 10.0, 11.9
ellipse(ax, LX, LY, 2.8, 0.95,
        "Login\n(Admin / Petugas)",
        bg=C_LOGIN_BG, bd=C_LOGIN_BD, lw=2.2, fs=9.2)

# ─────────────────────────────────────────────
# ADMIN Use-Cases  (zona bawah — SEBAR X agar memencar)
# ─────────────────────────────────────────────
adm_ucs = [
    ( 7.0, 10.5, "Kelola Data\nPoli & Dokter"),
    ( 9.6, 10.5, "Panggil &\nKelola Antrian"),
    (12.2, 10.5, "Kelola Jadwal\nPraktik Dokter"),
    ( 7.0,  8.8, "Riwayat &\nLaporan Antrian"),
    ( 9.6,  8.8, "Kelola Data\nPasien"),
    (12.2,  8.8, "Pengaturan\nLoket"),
]
for cx, cy, lbl in adm_ucs:
    ellipse(ax, cx, cy, 2.9, 0.85, lbl, bd=C_ADM_BD)

# Login -> setiap fitur admin (memencar, bukan rantai)
for cx, cy, _ in adm_ucs:
    # rad kecil, arah disesuaikan dengan posisi X relatif terhadap Login
    dx = cx - LX
    rad = dx * 0.03   # sedikit melengkung proporsional ke kiri/kanan
    arrow(ax, LX, LY - 0.48, cx, cy + 0.43,
          color=C_SYS_BD, lw=1.3, rad=rad)

# ─────────────────────────────────────────────
# PASIEN actor   (kiri atas)
# ─────────────────────────────────────────────
PA_X, PA_Y = 1.8, 16.0
actor(ax, PA_X, PA_Y, "Pasien", C_PASIEN)

# Pasien → fitur publik (solid biru, memencar)
for i, (cx, cy, _) in enumerate(pub_ucs):
    dx = cx - PA_X
    rad = -dx * 0.01
    arrow(ax, PA_X + 0.48, PA_Y + 0.1,
          cx - 1.55, cy,
          color=C_PASIEN, lw=1.4, rad=rad)

# ─────────────────────────────────────────────
# ADMIN actor   (kiri bawah)
# ─────────────────────────────────────────────
AD_X, AD_Y = 1.8, 9.8
actor(ax, AD_X, AD_Y, "Admin / Petugas", C_ADMIN)

# Admin → Login (solid merah, wajib)
arrow(ax, AD_X + 0.48, AD_Y + 0.3,
      LX - 1.40, LY,
      color=C_ADMIN, lw=1.9)

# Admin -> fitur publik (opsional, putus-putus merah, melengkung)
for i, (cx, cy, _) in enumerate(pub_ucs):
    dx = cx - AD_X
    rad = -(0.15 + i * 0.06)
    arrow(ax, AD_X + 0.48, AD_Y + 0.5,
          cx - 1.55, cy,
          color=C_ADMIN, lw=1.0, ls="dashed", rad=rad)

# ─────────────────────────────────────────────
# Legend
# ─────────────────────────────────────────────
lx, ly = 3.9, 1.55

ax.add_patch(mpatches.Ellipse((lx+0.38, ly+0.18), 0.52, 0.30,
    facecolor="#FFFFFF", edgecolor="#555555", lw=1.0, zorder=3))
ax.text(lx+0.70, ly+0.18, "= Use Case", va="center", fontsize=7.2, color="#333")

ax.add_patch(mpatches.Ellipse((lx+2.55, ly+0.18), 0.52, 0.30,
    facecolor=C_PUB_BG, edgecolor=C_PUB_BD, lw=1.0, zorder=3))
ax.text(lx+2.88, ly+0.18, "= Fitur Publik", va="center", fontsize=7.2, color="#333")

ax.add_patch(mpatches.Ellipse((lx+4.95, ly+0.18), 0.52, 0.30,
    facecolor=C_LOGIN_BG, edgecolor=C_LOGIN_BD, lw=1.4, zorder=3))
ax.text(lx+5.28, ly+0.18, "= Gerbang Login", va="center", fontsize=7.2, color="#333")

ax.annotate("", xy=(lx+7.35, ly+0.18), xytext=(lx+6.85, ly+0.18),
    arrowprops=dict(arrowstyle="->", color=C_PASIEN, lw=1.4))
ax.text(lx+7.43, ly+0.18, "= Akses Langsung",
        va="center", fontsize=7.2, color="#333")

ax.annotate("", xy=(lx+9.55, ly+0.18), xytext=(lx+8.98, ly+0.18),
    arrowprops=dict(arrowstyle="->", color=C_ADMIN, lw=1.3, linestyle="dashed"))
ax.text(lx+9.63, ly+0.18, "= Juga Bisa Akses",
        va="center", fontsize=7.2, color="#333")

# ─────────────────────────────────────────────
# Save
# ─────────────────────────────────────────────
OUTPUT = "alur_sistem.png"
fig.tight_layout(pad=0.5)
fig.savefig(OUTPUT, dpi=180, bbox_inches="tight",
            facecolor=fig.get_facecolor())
plt.close(fig)
print(f"[OK] Diagram berhasil disimpan: {OUTPUT}")
