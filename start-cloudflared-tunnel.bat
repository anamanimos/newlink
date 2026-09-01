@echo off
title Cloudflare Tunnel MySQL Client (db.nams.my.id -> 127.0.0.1:3307)
echo ============================================================
echo  Menghubungkan Cloudflare Tunnel ke db.nams.my.id
echo  Port Lokal: 127.0.0.1:3307
echo ============================================================
"C:\Program Files (x86)\cloudflared\cloudflared.exe" access tcp --hostname db.nams.my.id --url 127.0.0.1:3307
