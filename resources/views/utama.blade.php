<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VOLTgarage — Spare Part Kendaraan Listrik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════
       DESIGN TOKENS — ELECTRIC VOLT THEME
    ═══════════════════════════════════════ */
        :root {
            --void: #020408;
            --deep: #030810;
            --dark: #05101a;
            --mid: #081525;
            --surface: #0c1e30;
            --card: #0e2235;
            --border: #0f2d40;
            --border2: #1a3d54;

            --grey: #5a8099;
            --dim: #2a4a60;
            --chrome: #8fb8cc;
            --bright: #cce8f5;

            /* Volt colors */
            --volt: #00f5d4;
            --volt-mid: #00c9aa;
            --volt-cool: #00a08a;
            --volt-glow: rgba(0, 245, 212, 0.3);
            --volt-glow2: rgba(0, 245, 212, 0.1);

            /* Electric blue accent */
            --arc: #0af;
            --arc-glow: rgba(0, 170, 255, 0.25);

            /* Warning/energy yellow */
            --charge: #ffe22a;
            --charge-glow: rgba(255, 226, 42, 0.2);

            --font-display: 'Orbitron', monospace;
            --font-title: 'Syne', sans-serif;
            --font-body: 'Space Mono', monospace;

            --glow-main: 0 0 30px var(--volt-glow), 0 0 60px rgba(0, 245, 212, 0.1);
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            background: var(--void);
            color: var(--chrome);
            overflow-x: hidden;
        }

        /* Circuit board texture */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 245, 212, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 212, 0.02) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
            z-index: 9998;
        }

        /* Scanline flicker */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg,
                    transparent 0px,
                    transparent 3px,
                    rgba(0, 0, 0, 0.06) 3px,
                    rgba(0, 0, 0, 0.06) 4px);
            pointer-events: none;
            z-index: 9999;
            animation: scanFlicker 8s linear infinite;
        }

        @keyframes scanFlicker {

            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 0.6;
            }
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--deep);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--volt);
            border-radius: 2px;
            box-shadow: 0 0 8px var(--volt-glow);
        }

        /* ═══════════════════════════════
       CART FLOATING BUTTON
    ═══════════════════════════════ */
        .cart-fab {
            position: fixed;
            right: 24px;
            bottom: 80px;
            width: 52px;
            height: 52px;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--volt);
            z-index: 901;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 0 18px var(--volt-glow2);
        }

        .cart-fab:hover {
            background: rgba(0, 245, 212, 0.1);
            border-color: var(--volt);
            box-shadow: 0 0 28px var(--volt-glow);
            transform: translateY(-2px);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            background: var(--volt);
            color: var(--void);
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            box-shadow: 0 0 10px var(--volt-glow);
            animation: badgePop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            display: none;
        }

        .cart-badge.show {
            display: flex;
        }

        @keyframes badgePop {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        /* ═══════════════════════════════
       CART DRAWER
    ═══════════════════════════════ */
        .cart-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            bottom: 0;
            width: 420px;
            z-index: 1100;
            background: var(--card);
            border-left: 1px solid var(--border2);
            display: flex;
            flex-direction: column;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -20px 0 60px rgba(0, 0, 0, 0.7);
        }

        .cart-drawer.open {
            right: 0;
        }

        /* Top accent line */
        .cart-drawer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt), var(--arc), transparent);
            box-shadow: 0 0 10px var(--volt-glow);
        }

        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1099;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s;
            backdrop-filter: blur(4px);
        }

        .cart-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* Cart Header */
        .cart-head {
            padding: 20px 24px;
            background: var(--mid);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .cart-head-title {
            font-family: var(--font-display);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--volt);
            text-shadow: 0 0 10px rgba(0, 245, 212, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 3px;
            background: rgba(0, 245, 212, 0.08);
            border: 1px solid rgba(0, 245, 212, 0.2);
            color: var(--volt);
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cart-close-btn:hover {
            background: rgba(0, 245, 212, 0.2);
            border-color: var(--volt);
        }

        /* Cart Body */
        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-body::-webkit-scrollbar {
            width: 3px;
        }

        .cart-body::-webkit-scrollbar-track {
            background: var(--dark);
        }

        .cart-body::-webkit-scrollbar-thumb {
            background: var(--volt);
        }

        /* Empty state */
        .cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            gap: 16px;
            padding: 40px 20px;
            text-align: center;
        }

        .cart-empty i {
            font-size: 52px;
            color: var(--border2);
            animation: batteryPulse 2s ease-in-out infinite;
        }

        @keyframes batteryPulse {

            0%,
            100% {
                color: var(--border2);
            }

            50% {
                color: var(--dim);
            }
        }

        .cart-empty p {
            font-family: var(--font-display);
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--dim);
        }

        .cart-empty span {
            font-family: var(--font-title);
            font-size: 13px;
            color: var(--grey);
            line-height: 1.6;
        }

        /* Cart Items */
        .cart-item {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            gap: 12px;
            align-items: start;
            padding: 14px;
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 4px;
            margin-bottom: 10px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            animation: itemSlideIn 0.3s ease both;
        }

        @keyframes itemSlideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .cart-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--volt), var(--arc));
            box-shadow: 0 0 6px var(--volt-glow);
        }

        .cart-item:hover {
            background: var(--surface);
            border-color: var(--border2);
        }

        .cart-item-img {
            width: 72px;
            height: 60px;
            border-radius: 3px;
            object-fit: cover;
            border: 1px solid var(--border);
            filter: saturate(0.8);
        }

        .cart-item-info {
            min-width: 0;
        }

        .cart-item-cat {
            font-family: var(--font-display);
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--volt);
            margin-bottom: 3px;
        }

        .cart-item-name {
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--bright);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .cart-item-tipe {
            font-family: var(--font-title);
            font-size: 11px;
            color: var(--dim);
            margin-bottom: 8px;
        }

        .cart-qty-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 2px;
            background: var(--surface);
            border: 1px solid var(--border2);
            color: var(--chrome);
            font-size: 14px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font-display);
            flex-shrink: 0;
        }

        .qty-btn:hover {
            background: rgba(0, 245, 212, 0.1);
            border-color: var(--volt);
            color: var(--volt);
        }

        .qty-num {
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 700;
            color: var(--bright);
            min-width: 20px;
            text-align: center;
        }

        .cart-item-price {
            font-family: var(--font-display);
            font-size: 14px;
            color: var(--volt);
            text-shadow: 0 0 8px rgba(0, 245, 212, 0.3);
            white-space: nowrap;
            text-align: right;
        }

        .cart-item-price small {
            display: block;
            font-family: var(--font-title);
            font-size: 9px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--dim);
            margin-bottom: 2px;
            font-style: normal;
        }

        .cart-del-btn {
            display: block;
            margin-top: 6px;
            font-family: var(--font-display);
            font-size: 9px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 68, 68, 0.6);
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s;
            text-align: right;
            padding: 0;
        }

        .cart-del-btn:hover {
            color: #ff4444;
        }

        /* Cart Footer */
        .cart-foot {
            padding: 18px 24px;
            background: var(--mid);
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .cart-subtotal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .cart-subtotal-lbl {
            font-family: var(--font-display);
            font-size: 9px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--dim);
        }

        .cart-subtotal-val {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 900;
            color: var(--volt);
            text-shadow: 0 0 12px rgba(0, 245, 212, 0.3);
        }

        .cart-item-count {
            font-family: var(--font-title);
            font-size: 11px;
            color: var(--grey);
            margin-bottom: 14px;
        }

        .cart-separator {
            border: none;
            border-top: 1px solid var(--border);
            margin: 12px 0;
        }

        .btn-checkout {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--void);
            background: var(--volt);
            border: none;
            border-radius: 3px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 0 20px var(--volt-glow);
            position: relative;
            overflow: hidden;
        }

        .btn-checkout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-checkout:hover::before {
            left: 100%;
        }

        .btn-checkout:hover {
            box-shadow: 0 0 35px var(--volt-glow);
            background: #20ffe5;
        }

        .btn-checkout:disabled {
            background: var(--dim);
            box-shadow: none;
            cursor: not-allowed;
            color: var(--grey);
        }

        .btn-clear-cart {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: var(--font-display);
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: 3px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-clear-cart:hover {
            border-color: rgba(255, 68, 68, 0.4);
            color: #ff6666;
            background: rgba(255, 68, 68, 0.05);
        }

        /* ── Add to cart notification ── */
        .cart-toast {
            position: fixed;
            bottom: 150px;
            right: 24px;
            z-index: 1200;
            background: var(--surface);
            border: 1px solid var(--volt);
            border-radius: 4px;
            padding: 12px 18px;
            font-family: var(--font-display);
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--volt);
            box-shadow: 0 0 20px var(--volt-glow), 0 10px 30px rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .cart-toast.show {
            transform: translateX(0);
        }

        .cart-toast i {
            font-size: 16px;
        }

        /* ── Cart nav button (in header) ── */
        .cart-nav-btn {
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-title);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 3px;
            transition: all 0.2s;
            cursor: pointer;
            background: none;
            border: none;
        }

        .cart-nav-btn:hover {
            color: var(--volt);
            background: rgba(0, 245, 212, 0.06);
        }

        .cart-nav-badge {
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            background: var(--volt);
            color: var(--void);
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 700;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 0 8px var(--volt-glow);
        }

        .cart-nav-badge.show {
            display: flex;
        }

        @media(max-width:576px) {
            .cart-drawer {
                width: 100%;
                right: -100%;
            }

            .cart-drawer.open {
                right: 0;
            }
        }

        /* ═══════════════════════════════
       HEADER
    ═══════════════════════════════ */
        #header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 44px;
            background: rgba(2, 4, 8, 0.95);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid var(--border);
        }

        /* Volt line at top */
        #header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, var(--volt-cool) 20%, var(--volt) 50%, var(--arc) 70%, transparent 100%);
            box-shadow: 0 0 14px var(--volt-glow);
            animation: headerLine 4s ease-in-out infinite;
        }

        @keyframes headerLine {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .logo-link {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-glyph {
            width: 44px;
            height: 44px;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .logo-glyph::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 245, 212, 0.15), rgba(0, 170, 255, 0.15));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .logo-link:hover .logo-glyph::before {
            opacity: 1;
        }

        .logo-link:hover .logo-glyph {
            border-color: var(--volt);
            box-shadow: 0 0 20px var(--volt-glow2), inset 0 0 20px rgba(0, 245, 212, 0.05);
        }

        .logo-glyph svg {
            position: relative;
            z-index: 1;
        }

        .logo-name {
            font-family: var(--font-display);
            font-size: 18px;
            letter-spacing: 4px;
            color: var(--bright);
            display: block;
            line-height: 1;
            font-weight: 700;
        }

        .logo-name em {
            font-style: normal;
            color: var(--volt);
            text-shadow: 0 0 12px var(--volt-glow);
        }

        .logo-tag {
            font-family: var(--font-title);
            font-size: 8px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--dim);
            display: block;
            margin-top: 4px;
        }

        .nav-list {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .nav-list li a {
            font-family: var(--font-title);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 3px;
            transition: all 0.2s;
        }

        .nav-list li a:hover {
            color: var(--volt);
            background: rgba(0, 245, 212, 0.06);
        }

        .btn-nav-login {
            color: var(--bright) !important;
            border: 1px solid var(--border2) !important;
        }

        .btn-nav-login:hover {
            border-color: var(--volt) !important;
            color: var(--volt) !important;
            background: rgba(0, 245, 212, 0.06) !important;
            box-shadow: 0 0 12px var(--volt-glow2) !important;
        }

        .btn-nav-register {
            font-family: var(--font-display) !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            letter-spacing: 2px !important;
            text-transform: uppercase !important;
            color: var(--void) !important;
            background: var(--volt) !important;
            padding: 9px 22px !important;
            border-radius: 3px !important;
            border: none !important;
            text-decoration: none !important;
            transition: all 0.25s !important;
            box-shadow: 0 0 20px var(--volt-glow) !important;
        }

        .btn-nav-register:hover {
            box-shadow: 0 0 35px var(--volt-glow), 0 6px 24px rgba(0, 245, 212, 0.4) !important;
            transform: translateY(-1px) !important;
            background: #20ffe5 !important;
        }

        .user-toggle {
            font-family: var(--font-display);
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--volt) !important;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-av {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--volt), var(--arc));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--void);
            font-weight: 700;
            font-size: 12px;
            flex-shrink: 0;
            font-family: var(--font-display);
            box-shadow: 0 0 12px var(--volt-glow);
        }

        .dd-panel {
            background: var(--card) !important;
            border: 1px solid var(--border2) !important;
            border-radius: 8px !important;
            padding: 8px !important;
            min-width: 230px !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 30px rgba(0, 245, 212, 0.05) !important;
        }

        .dd-panel a {
            font-family: var(--font-title);
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--chrome);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .dd-panel a:hover {
            background: var(--surface);
            color: var(--volt);
        }

        .role-badge {
            font-family: var(--font-display);
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: rgba(0, 245, 212, 0.08);
            border: 1px solid rgba(0, 245, 212, 0.25);
            color: var(--volt);
            border-radius: 3px;
            padding: 4px 10px;
            display: block;
            margin: 4px 4px 8px;
        }

        .btn-out {
            font-family: var(--font-title);
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--volt) !important;
            background: rgba(0, 245, 212, 0.07) !important;
            border: 1px solid rgba(0, 245, 212, 0.2) !important;
            border-radius: 3px;
            padding: 9px 14px;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-out:hover {
            background: rgba(0, 245, 212, 0.15) !important;
            border-color: var(--volt) !important;
        }

        .mob-tog {
            display: none;
            font-size: 24px;
            color: var(--volt);
            background: none;
            border: none;
            cursor: pointer;
        }

        /* ═══════════════════════════════
       HERO
    ═══════════════════════════════ */
        #hero {
            min-height: 100vh;
            background: var(--void);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding-top: 68px;
        }

        /* Electric field background */
        .ev-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 60% 50% at 70% 50%, rgba(0, 245, 212, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 20% 50%, rgba(0, 170, 255, 0.04) 0%, transparent 60%);
        }

        /* Diagonal tech panel */
        .hero-panel {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 55%;
            clip-path: polygon(8% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: var(--dark);
            border-left: 1px solid rgba(0, 245, 212, 0.15);
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 245, 212, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 212, 0.03) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 60% at 65% 50%, rgba(0, 245, 212, 0.05) 0%, transparent 70%);
        }

        /* Volt glow orb */
        .volt-orb {
            position: absolute;
            right: 22%;
            top: 50%;
            transform: translate(50%, -50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 245, 212, 0.08) 0%, rgba(0, 170, 255, 0.04) 40%, transparent 70%);
            pointer-events: none;
            animation: orbPulse 4s ease-in-out infinite;
        }

        @keyframes orbPulse {

            0%,
            100% {
                transform: translate(50%, -50%) scale(1);
                opacity: 0.7;
            }

            50% {
                transform: translate(50%, -50%) scale(1.15);
                opacity: 1;
            }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--volt);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--volt-glow);
            animation: floatParticle linear infinite;
            pointer-events: none;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 1300px;
            margin: 0 auto;
            padding: 80px 44px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        /* ─ LEFT ─ */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--volt);
            margin-bottom: 24px;
            animation: slideUp 0.7s ease both;
        }

        .hero-eyebrow .charge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--volt);
            animation: chargePulse 2s ease-in-out infinite;
            box-shadow: 0 0 10px var(--volt-glow);
        }

        .hero-eyebrow::before {
            content: '';
            display: block;
            width: 30px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--volt));
        }

        h1.hero-h1 {
            font-family: var(--font-display);
            font-size: clamp(52px, 7vw, 102px);
            line-height: 0.88;
            text-transform: uppercase;
            color: var(--bright);
            margin-bottom: 26px;
            font-weight: 900;
            animation: slideUp 0.7s 0.08s ease both;
        }

        h1.hero-h1 .volt-text {
            display: block;
            color: var(--volt);
            text-shadow: 0 0 30px rgba(0, 245, 212, 0.6), 0 0 60px rgba(0, 245, 212, 0.2);
            animation: textGlow 3s ease-in-out infinite;
        }

        @keyframes textGlow {

            0%,
            100% {
                text-shadow: 0 0 30px rgba(0, 245, 212, 0.6), 0 0 60px rgba(0, 245, 212, 0.2);
            }

            50% {
                text-shadow: 0 0 50px rgba(0, 245, 212, 0.9), 0 0 100px rgba(0, 245, 212, 0.3);
            }
        }

        h1.hero-h1 .ghost-text {
            display: block;
            -webkit-text-stroke: 1px rgba(0, 245, 212, 0.12);
            color: transparent;
        }

        .hero-desc {
            font-size: 13px;
            line-height: 2;
            color: var(--grey);
            max-width: 430px;
            margin-bottom: 38px;
            font-family: var(--font-title);
            font-size: 14px;
            animation: slideUp 0.7s 0.16s ease both;
        }

        .cta-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            animation: slideUp 0.7s 0.24s ease both;
        }

        .cta-volt {
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--void);
            background: var(--volt);
            padding: 14px 30px;
            border-radius: 3px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 0 24px var(--volt-glow), 0 6px 20px rgba(0, 245, 212, 0.3);
            position: relative;
            overflow: hidden;
        }

        .cta-volt::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .cta-volt:hover::before {
            left: 100%;
        }

        .cta-volt:hover {
            transform: translateY(-3px);
            color: var(--void);
            box-shadow: 0 0 40px var(--volt-glow), 0 12px 36px rgba(0, 245, 212, 0.4);
            background: #20ffe5;
        }

        .cta-wire {
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--chrome);
            padding: 13px 28px;
            border: 1px solid var(--border2);
            border-radius: 3px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .cta-wire:hover {
            border-color: var(--volt);
            color: var(--volt);
            background: rgba(0, 245, 212, 0.05);
            box-shadow: 0 0 16px var(--volt-glow2);
        }

        /* Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            margin-top: 48px;
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
            animation: slideUp 0.7s 0.32s ease both;
            position: relative;
        }

        .stats-row::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--volt), var(--arc), transparent);
            opacity: 0.5;
        }

        .stat-cell {
            padding: 20px;
            border-right: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: background 0.25s;
        }

        .stat-cell:last-child {
            border-right: none;
        }

        .stat-cell:hover {
            background: var(--surface);
        }

        .stat-cell::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt), var(--arc));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s;
            box-shadow: 0 0 8px var(--volt-glow);
        }

        .stat-cell:hover::after {
            transform: scaleX(1);
        }

        .stat-num {
            font-family: var(--font-display);
            font-size: 32px;
            color: var(--bright);
            line-height: 1;
            font-weight: 900;
        }

        .stat-num sup {
            font-size: 16px;
            color: var(--volt);
            vertical-align: top;
            margin-top: 4px;
            display: inline-block;
        }

        .stat-lbl {
            font-family: var(--font-title);
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--dim);
            margin-top: 5px;
        }

        /* ─ RIGHT — EV CAR SVG ─ */
        .ev-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: slideRight 0.8s 0.2s ease both;
        }

        .ev-svg-wrap {
            width: 100%;
            max-width: 560px;
            filter: drop-shadow(0 20px 60px rgba(0, 245, 212, 0.15)) drop-shadow(0 0 100px rgba(0, 0, 0, 0.95));
            position: relative;
            z-index: 2;
        }

        /* EV Car Animations */
        .wheel-fl {
            transform-origin: 120px 290px;
            animation: wheelSpin 2s linear infinite;
        }

        .wheel-fr {
            transform-origin: 380px 290px;
            animation: wheelSpin 2s linear infinite;
        }

        .wheel-rl {
            transform-origin: 120px 290px;
            animation: wheelSpin 2s linear infinite;
        }

        .wheel-rr {
            transform-origin: 380px 290px;
            animation: wheelSpin 2s linear infinite;
        }

        .battery-charge {
            animation: batteryFlow 1.5s ease-in-out infinite;
        }

        .battery-cell1 {
            animation: cellPulse 1.5s 0s ease-in-out infinite;
        }

        .battery-cell2 {
            animation: cellPulse 1.5s 0.2s ease-in-out infinite;
        }

        .battery-cell3 {
            animation: cellPulse 1.5s 0.4s ease-in-out infinite;
        }

        .battery-cell4 {
            animation: cellPulse 1.5s 0.6s ease-in-out infinite;
        }

        .battery-cell5 {
            animation: cellPulse 1.5s 0.8s ease-in-out infinite;
        }

        .motor-spin {
            transform-origin: 230px 275px;
            animation: motorSpin 1s linear infinite;
        }

        .motor-spin2 {
            transform-origin: 230px 275px;
            animation: motorSpin2 1.5s linear infinite;
        }

        .arc-bolt {
            animation: boltFlash 1.5s ease-in-out infinite;
        }

        .arc-bolt2 {
            animation: boltFlash 1.5s 0.75s ease-in-out infinite;
        }

        .hud-scan {
            animation: hudScan 2s ease-in-out infinite;
        }

        .hud-bar1 {
            animation: hudBar 1.8s 0s ease-in-out infinite;
        }

        .hud-bar2 {
            animation: hudBar 1.8s 0.3s ease-in-out infinite;
        }

        .hud-bar3 {
            animation: hudBar 1.8s 0.6s ease-in-out infinite;
        }

        .energy-flow {
            animation: energyFlow 1.2s linear infinite;
            stroke-dasharray: 6 4;
            stroke-dashoffset: 0;
        }

        .energy-flow2 {
            animation: energyFlow2 1.2s linear infinite;
            stroke-dasharray: 6 4;
            stroke-dashoffset: 0;
        }

        .headlight-l {
            animation: headlightGlow 2s ease-in-out infinite;
        }

        .headlight-r {
            animation: headlightGlow 2s 1s ease-in-out infinite;
        }

        .ground-glow {
            animation: groundPulse 2s ease-in-out infinite;
        }

        @keyframes wheelSpin {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(360deg)
            }
        }

        @keyframes motorSpin {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(360deg)
            }
        }

        @keyframes motorSpin2 {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(-360deg)
            }
        }

        @keyframes batteryFlow {

            0%,
            100% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
                filter: drop-shadow(0 0 4px var(--volt));
            }
        }

        @keyframes cellPulse {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 1;
            }
        }

        @keyframes boltFlash {

            0%,
            100% {
                opacity: 0;
            }

            40%,
            60% {
                opacity: 1;
            }
        }

        @keyframes hudScan {
            0% {
                transform: translateY(0);
                opacity: 0.8;
            }

            100% {
                transform: translateY(60px);
                opacity: 0;
            }
        }

        @keyframes hudBar {

            0%,
            100% {
                transform: scaleX(0.3);
            }

            50% {
                transform: scaleX(1);
            }
        }

        @keyframes energyFlow {
            from {
                stroke-dashoffset: 0
            }

            to {
                stroke-dashoffset: -40
            }
        }

        @keyframes energyFlow2 {
            from {
                stroke-dashoffset: 0
            }

            to {
                stroke-dashoffset: 40
            }
        }

        @keyframes headlightGlow {

            0%,
            100% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }
        }

        @keyframes groundPulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scaleX(1);
            }

            50% {
                opacity: 0.6;
                transform: scaleX(1.05);
            }
        }

        @keyframes chargePulse {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 10px var(--volt-glow);
            }

            50% {
                opacity: 0.3;
                box-shadow: none;
            }
        }

        /* EV corner decorations */
        .ev-corner {
            position: absolute;
            width: 50px;
            height: 50px;
            pointer-events: none;
        }

        .ev-corner.tl {
            top: 0;
            left: 0;
            border-top: 1px solid var(--volt);
            border-left: 1px solid var(--volt);
            opacity: 0.6;
        }

        .ev-corner.br {
            bottom: 0;
            right: 0;
            border-bottom: 1px solid var(--volt);
            border-right: 1px solid var(--volt);
            opacity: 0.6;
        }

        /* Charge percentage display */
        .charge-hud {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 20, 30, 0.85);
            border: 1px solid rgba(0, 245, 212, 0.25);
            border-radius: 6px;
            padding: 12px 16px;
            backdrop-filter: blur(10px);
            animation: slideUp 1s 0.5s ease both;
        }

        .charge-pct {
            font-family: var(--font-display);
            font-size: 28px;
            color: var(--volt);
            text-shadow: 0 0 14px var(--volt-glow);
            line-height: 1;
            font-weight: 900;
        }

        .charge-lbl {
            font-family: var(--font-title);
            font-size: 9px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--dim);
            margin-top: 2px;
        }

        .charge-bar-wrap {
            width: 80px;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .charge-bar-fill {
            height: 100%;
            width: 87%;
            background: linear-gradient(90deg, var(--volt-cool), var(--volt));
            border-radius: 2px;
            box-shadow: 0 0 6px var(--volt-glow);
            animation: chargeFill 3s ease-in-out infinite;
        }

        @keyframes chargeFill {

            0%,
            100% {
                width: 87%;
            }

            50% {
                width: 92%;
            }
        }

        /* ═══════════════════════════════
       TICKER
    ═══════════════════════════════ */
        .ticker {
            overflow: hidden;
            white-space: nowrap;
            padding: 11px 0;
            background: linear-gradient(90deg, var(--volt-cool), var(--volt) 40%, var(--arc) 70%, var(--volt-cool));
            position: relative;
        }

        .ticker::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(90deg, transparent 0px, rgba(255, 255, 255, 0.05) 1px, transparent 2px, transparent 10px);
        }

        .ticker-track {
            display: inline-flex;
            animation: tickScroll 32s linear infinite;
        }

        .t-item {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--void);
            padding: 0 24px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .t-dot {
            width: 4px;
            height: 4px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════
       TRUST BAND
    ═══════════════════════════════ */
        .trust-band {
            background: var(--dark);
            border-bottom: 1px solid var(--border);
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .trust-cell {
            padding: 30px 28px;
            border-right: 1px solid var(--border);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            position: relative;
            overflow: hidden;
            transition: background 0.3s;
        }

        .trust-cell:last-child {
            border-right: none;
        }

        .trust-cell:hover {
            background: var(--surface);
        }

        .trust-cell::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt), var(--arc));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s;
            box-shadow: 0 0 8px var(--volt-glow);
        }

        .trust-cell:hover::after {
            transform: scaleX(1);
        }

        .t-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--volt);
            transition: all 0.3s;
        }

        .trust-cell:hover .t-icon {
            background: rgba(0, 245, 212, 0.08);
            border-color: rgba(0, 245, 212, 0.35);
            box-shadow: 0 0 16px rgba(0, 245, 212, 0.1);
        }

        .t-ttl {
            font-family: var(--font-title);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--bright);
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .t-dsc {
            font-family: var(--font-title);
            font-size: 12px;
            color: var(--grey);
            line-height: 1.5;
        }

        /* ═══════════════════════════════
       PRODUCTS
    ═══════════════════════════════ */
        #produk {
            padding: 96px 0;
            background: var(--void);
        }

        .s-eye {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--volt);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .s-eye::before,
        .s-eye::after {
            content: '';
            flex: 1;
            max-width: 70px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--volt));
            box-shadow: 0 0 6px var(--volt-glow);
        }

        .s-eye::after {
            background: linear-gradient(90deg, var(--volt), transparent);
        }

        .s-ttl {
            font-family: var(--font-display);
            font-size: clamp(40px, 5.5vw, 72px);
            text-transform: uppercase;
            color: var(--bright);
            text-align: center;
            line-height: 0.9;
            margin-bottom: 16px;
            font-weight: 900;
        }

        .s-ttl span {
            color: var(--volt);
            text-shadow: 0 0 20px rgba(0, 245, 212, 0.4);
        }

        .s-sub {
            font-family: var(--font-title);
            font-size: 14px;
            color: var(--grey);
            text-align: center;
            margin-bottom: 50px;
        }

        /* Filter */
        .flt-list {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 52px;
            list-style: none;
            padding: 0;
        }

        .flt-list li {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            padding: 8px 20px;
            border: 1px solid var(--border);
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
        }

        .flt-list li:hover {
            color: var(--volt);
            border-color: rgba(0, 245, 212, 0.3);
            background: rgba(0, 245, 212, 0.04);
        }

        .flt-list li.active {
            color: var(--void);
            background: var(--volt);
            border-color: transparent;
            box-shadow: 0 0 20px var(--volt-glow), 0 4px 18px rgba(0, 245, 212, 0.3);
        }

        /* Product Grid */
        .prod-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid var(--border);
            border-left: 1px solid var(--border);
        }

        .p-card {
            background: var(--dark);
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: background 0.3s;
        }

        .p-card:hover {
            background: var(--surface);
            z-index: 2;
        }

        /* Volt scan on hover */
        .p-card::after {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            right: 0;
            height: 40%;
            background: linear-gradient(180deg, transparent, rgba(0, 245, 212, 0.04), transparent);
            transition: top 0.7s;
        }

        .p-card:hover::after {
            top: 120%;
        }

        /* Top volt accent */
        .p-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt), var(--arc));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s;
            z-index: 3;
            box-shadow: 0 0 8px var(--volt-glow);
        }

        .p-card:hover::before {
            transform: scaleX(1);
        }

        .p-img {
            height: 220px;
            background: var(--mid);
            overflow: hidden;
            position: relative;
        }

        .p-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
            filter: saturate(0.85) hue-rotate(10deg);
        }

        .p-card:hover .p-img img {
            transform: scale(1.07);
            filter: saturate(1.1);
        }

        .p-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(2, 4, 8, 0.75) 0%, transparent 50%);
        }

        .p-over {
            position: absolute;
            inset: 0;
            z-index: 4;
            background: rgba(2, 4, 8, 0.78);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .p-card:hover .p-over {
            opacity: 1;
        }

        .o-btn {
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 9px 16px;
            border-radius: 2px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .o-view {
            background: rgba(143, 184, 204, 0.1);
            border: 1px solid rgba(143, 184, 204, 0.2);
            color: var(--bright);
        }

        .o-view:hover {
            background: rgba(143, 184, 204, 0.2);
            color: #fff;
        }

        .o-buy {
            background: var(--volt);
            color: var(--void);
            box-shadow: 0 0 16px var(--volt-glow);
        }

        .o-buy:hover {
            box-shadow: 0 0 28px var(--volt-glow);
            background: #20ffe5;
        }

        .p-body {
            padding: 18px 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .p-jenis {
            font-family: var(--font-display);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--volt);
            margin-bottom: 6px;
        }

        .p-name {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--bright);
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .p-tipe {
            font-family: var(--font-title);
            font-size: 12px;
            color: var(--dim);
            margin-bottom: auto;
            padding-bottom: 14px;
        }

        .p-foot {
            border-top: 1px solid var(--border);
            padding-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .p-price {
            font-family: var(--font-display);
            font-size: 20px;
            color: var(--volt);
            line-height: 1;
            text-shadow: 0 0 10px rgba(0, 245, 212, 0.3);
        }

        .p-price small {
            font-family: var(--font-title);
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--dim);
            display: block;
            margin-bottom: 2px;
            font-style: normal;
        }

        .p-stk {
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sdot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: sdotBlink 2s ease-in-out infinite;
        }

        .s-ok {
            color: #00f5a0;
        }

        .s-ok .sdot {
            background: #00f5a0;
            box-shadow: 0 0 6px rgba(0, 245, 160, 0.5);
        }

        .s-low {
            color: #ffe22a;
        }

        .s-low .sdot {
            background: #ffe22a;
            box-shadow: 0 0 6px rgba(255, 226, 42, 0.5);
        }

        .s-out {
            color: #ff4444;
        }

        .s-out .sdot {
            background: #ff4444;
            box-shadow: 0 0 6px rgba(255, 68, 68, 0.5);
        }

        .p-num {
            position: absolute;
            bottom: -8px;
            right: 14px;
            font-family: var(--font-display);
            font-size: 88px;
            color: var(--border);
            line-height: 1;
            user-select: none;
            pointer-events: none;
            transition: color 0.4s;
        }

        .p-card:hover .p-num {
            color: rgba(0, 245, 212, 0.04);
        }

        /* ═══════════════════════════════
       MODAL
    ═══════════════════════════════ */
        .modal-content {
            background: var(--card);
            border: 1px solid var(--border2);
            border-radius: 8px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--mid);
            border-bottom: 1px solid var(--border);
            padding: 18px 26px;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt) 0%, var(--arc) 60%, transparent);
            box-shadow: 0 0 8px var(--volt-glow);
        }

        .modal-title {
            font-family: var(--font-display);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--volt);
            text-shadow: 0 0 10px rgba(0, 245, 212, 0.3);
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.5;
        }

        .modal-body {
            padding: 0;
        }

        .m-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .m-info {
            padding: 22px 26px 6px;
        }

        .m-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
        }

        .m-row:last-of-type {
            border: none;
        }

        .m-lbl {
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--dim);
        }

        .m-val {
            font-family: var(--font-title);
            font-size: 15px;
            font-weight: 600;
            color: var(--bright);
        }

        .m-val.heat {
            font-family: var(--font-display);
            font-size: 24px;
            color: var(--volt);
            text-shadow: 0 0 10px rgba(0, 245, 212, 0.3);
        }

        .m-form {
            padding: 18px 26px 4px;
        }

        .f-lbl {
            font-family: var(--font-display);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--grey);
            display: block;
            margin-bottom: 8px;
        }

        .f-inp {
            background: var(--mid);
            border: 1px solid var(--border2);
            color: var(--bright);
            border-radius: 3px;
            padding: 12px 16px;
            font-size: 16px;
            width: 100%;
            outline: none;
            transition: all 0.2s;
            font-family: var(--font-display);
        }

        .f-inp:focus {
            border-color: var(--volt);
            box-shadow: 0 0 0 3px rgba(0, 245, 212, 0.1);
        }

        .total-wrap {
            background: rgba(0, 245, 212, 0.05);
            border: 1px solid rgba(0, 245, 212, 0.15);
            border-radius: 3px;
            padding: 14px 18px;
            margin-top: 10px;
            display: none;
        }

        .total-wrap.on {
            display: block;
        }

        .total-lbl {
            font-family: var(--font-display);
            font-size: 8px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--dim);
        }

        .total-val {
            font-family: var(--font-display);
            font-size: 28px;
            color: var(--volt);
            margin-top: 4px;
            text-shadow: 0 0 10px rgba(0, 245, 212, 0.3);
        }

        .modal-footer {
            background: var(--mid);
            border-top: 1px solid var(--border);
            padding: 14px 26px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .m-cancel {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: 3px;
            padding: 9px 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .m-cancel:hover {
            border-color: var(--grey);
            color: var(--bright);
        }

        /* ═══════════════════════════════
       FOOTER
    ═══════════════════════════════ */
        #footer {
            background: var(--deep);
            border-top: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        #footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--volt), var(--arc) 50%, transparent);
            box-shadow: 0 0 10px var(--volt-glow);
        }

        .footer-top {
            padding: 56px 44px 36px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 60px;
            align-items: start;
        }

        .f-brand-name {
            font-family: var(--font-display);
            font-size: 34px;
            color: var(--bright);
            line-height: 1;
            margin-bottom: 4px;
            font-weight: 900;
        }

        .f-brand-name em {
            font-style: normal;
            color: var(--volt);
            text-shadow: 0 0 14px var(--volt-glow);
        }

        .f-brand-tag {
            font-family: var(--font-title);
            font-size: 10px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--dim);
        }

        .f-brand-desc {
            font-family: var(--font-title);
            font-size: 13px;
            color: var(--grey);
            max-width: 340px;
            line-height: 1.8;
            margin-top: 14px;
        }

        .f-nav {
            display: flex;
            gap: 4px;
        }

        .f-nav a {
            font-family: var(--font-display);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--grey);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 3px;
            transition: all 0.2s;
        }

        .f-nav a:hover {
            color: var(--volt);
            background: rgba(0, 245, 212, 0.06);
        }

        .footer-btm {
            border-top: 1px solid var(--border);
            padding: 18px 44px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .f-copy {
            font-family: var(--font-title);
            font-size: 12px;
            color: var(--dim);
        }

        .f-copy a {
            color: var(--volt);
            text-decoration: none;
        }

        /* ═══════════════════════════════
       BACK TO TOP
    ═══════════════════════════════ */
        .btt {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 44px;
            height: 44px;
            background: var(--volt);
            color: var(--void);
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            text-decoration: none;
            z-index: 900;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s;
            box-shadow: 0 0 20px var(--volt-glow);
        }

        .btt:hover {
            transform: translateY(-4px);
            color: var(--void);
            box-shadow: 0 0 35px var(--volt-glow);
        }

        .btt.on {
            opacity: 1;
            pointer-events: auto;
        }

        /* ═══════════════════════════════
       KEYFRAMES
    ═══════════════════════════════ */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(44px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes tickScroll {
            from {
                transform: translateX(0)
            }

            to {
                transform: translateX(-50%)
            }
        }

        @keyframes sdotBlink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.25
            }
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ═══════════════════════════════
       RESPONSIVE
    ═══════════════════════════════ */
        @media(max-width:991px) {
            #header {
                padding: 0 20px;
            }

            .nav-list {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 68px;
                left: 0;
                right: 0;
                background: var(--deep);
                border-bottom: 1px solid var(--border);
                padding: 14px 20px;
                gap: 4px;
            }

            .nav-list.open {
                display: flex;
            }

            .mob-tog {
                display: block;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 60px 24px;
            }

            .ev-wrap {
                order: -1;
            }

            .ev-svg-wrap {
                max-width: 360px;
            }

            .trust-grid {
                grid-template-columns: 1fr 1fr;
            }

            .trust-cell {
                border-bottom: 1px solid var(--border);
            }

            .prod-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-top {
                grid-template-columns: 1fr;
                gap: 28px;
                padding: 40px 24px 28px;
            }

            .footer-btm {
                padding: 16px 24px;
            }
        }

        @media(max-width:576px) {
            h1.hero-h1 {
                font-size: 48px;
            }

            .prod-grid {
                grid-template-columns: 1fr;
            }

            .trust-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr 1fr;
            }

            .f-nav {
                flex-wrap: wrap;
            }

            .charge-hud {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Floating particles -->
    <div class="particle" style="left:10%;animation-duration:8s;animation-delay:0s;"></div>
    <div class="particle" style="left:25%;animation-duration:11s;animation-delay:2s;background:var(--arc);"></div>
    <div class="particle" style="left:50%;animation-duration:9s;animation-delay:4s;"></div>
    <div class="particle"
        style="left:70%;animation-duration:13s;animation-delay:1s;background:var(--charge);width:2px;height:2px;"></div>
    <div class="particle" style="left:85%;animation-duration:10s;animation-delay:6s;background:var(--arc);"></div>
    <div class="particle" style="left:40%;animation-duration:14s;animation-delay:3s;"></div>

    <!-- ══════════ HEADER ══════════ -->
    <header id="header">
        <a href="/" class="logo-link">
            <div class="logo-glyph">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <!-- Lightning bolt EV logo -->
                    <path d="M15 3L7 14h7l-3 9 11-11h-7L15 3z" fill="var(--volt)" opacity="0.9" />
                    <path d="M15 3L7 14h7l-3 9 11-11h-7L15 3z" fill="none" stroke="var(--volt)" stroke-width="0.5"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <div>
                <span class="logo-name"><em>VOLT</em>GARAG</span>
                <span class="logo-tag">Electric Vehicle Parts</span>
            </div>
        </a>

        <nav>
            <ul class="nav-list" id="navList">
                <li><a href="/">Home</a></li>
                <li><a href="#produk">Produk</a></li>
                <li>
                    @if(Auth::check() && Auth::user()->role == 'Guest')
                    <button class="cart-nav-btn" onclick="toggleCart()" title="Keranjang Belanja">
                        <i class="bi bi-bag-fill"></i> Keranjang
                        <span class="cart-nav-badge" id="cartNavBadge">0</span>
                    </button>
                     @endif
                </li>
                @if(!Auth::check())
                    <li><a href="/admin" class="btn-nav-login">Login</a></li>
                    <li><a href="/register" class="btn-nav-register">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a href="#" class="user-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-av">{{ strtoupper(substr(Auth::user()->email, 0, 1)) }}</div>
                            {{ Str::limit(Auth::user()->email, 22) }}
                        </a>
                        <ul class="dropdown-menu dd-panel dropdown-menu-end">
                            <span class="role-badge">⚡ Role: {{ Auth::user()->role }}</span>
                            <li><a href="/admin"><i class="bi bi-speedometer2 me-1"></i> Panel Dashboard</a></li>
                            <hr style="border-color:var(--border);margin:8px 0">
                            <li class="px-2 pb-1">
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn-out" type="submit">
                                        <i class="bi bi-power me-1"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>

        <button class="mob-tog" onclick="document.getElementById('navList').classList.toggle('open')">
            <i class="bi bi-list"></i>
        </button>
    </header>


    <!-- ══════════ HERO ══════════ -->
    <section id="hero">
        <div class="ev-bg"></div>
        <div class="hero-panel"></div>
        <div class="volt-orb"></div>

        <div class="hero-content">
            <!-- LEFT -->
            <div>
                <div class="hero-eyebrow"><span class="charge-dot"></span>Spesialis Spare Part EV</div>

                <h1 class="hero-h1">
                    Masa Depan
                    <span class="volt-text">Bertenaga</span>
                    <span class="ghost-text">Listrik</span>
                </h1>

                <p class="hero-desc">
                    Spare part original &amp; aftermarket untuk kendaraan listrik.
                    Baterai, motor drive, inverter, dan komponen EV berkualitas premium — bergaransi resmi.
                </p>

                <div class="cta-row">
                    <a href="#produk" class="cta-volt">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M9 2L4 9h4l-2 5 7-7H9L11 2z" />
                        </svg>
                        Belanja Sekarang
                    </a>
                    <a href="/admin" class="cta-wire">
                        <i class="bi bi-speedometer"></i> Dashboard
                    </a>
                </div>

            </div>

            <!-- RIGHT — EV CAR SVG -->
            <div class="ev-wrap">
                <div class="ev-corner tl"></div>
                <div class="ev-corner br"></div>

                <!-- Charge HUD overlay -->
                <div class="charge-hud">
                    <div class="charge-pct">87%</div>
                    <div class="charge-lbl">Battery Level</div>
                    <div class="charge-bar-wrap">
                        <div class="charge-bar-fill"></div>
                    </div>
                </div>

                <div class="ev-svg-wrap">
                    <svg viewBox="0 0 500 380" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <defs>
                            <!-- Body gradient -->
                            <linearGradient id="bodyGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#1a3a50" />
                                <stop offset="40%" stop-color="#0e2235" />
                                <stop offset="100%" stop-color="#081525" />
                            </linearGradient>
                            <!-- Roof gradient -->
                            <linearGradient id="roofGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1e4060" />
                                <stop offset="100%" stop-color="#0c1e30" />
                            </linearGradient>
                            <!-- Wheel gradient -->
                            <radialGradient id="wheelGrad" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" stop-color="#253a50" />
                                <stop offset="60%" stop-color="#0c1a28" />
                                <stop offset="100%" stop-color="#060e18" />
                            </radialGradient>
                            <!-- Volt glow -->
                            <radialGradient id="voltGlow" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" stop-color="#00f5d4" stop-opacity="0.8" />
                                <stop offset="50%" stop-color="#00c9aa" stop-opacity="0.4" />
                                <stop offset="100%" stop-color="#00f5d4" stop-opacity="0" />
                            </radialGradient>
                            <!-- Arc blue -->
                            <radialGradient id="arcGlow" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" stop-color="#00aaff" stop-opacity="0.9" />
                                <stop offset="100%" stop-color="#00aaff" stop-opacity="0" />
                            </radialGradient>
                            <!-- Battery cell -->
                            <linearGradient id="battCell" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#00c9aa" stop-opacity="0.6" />
                                <stop offset="100%" stop-color="#00f5d4" stop-opacity="1" />
                            </linearGradient>
                            <!-- Glass gradient -->
                            <linearGradient id="glassGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1a4060" stop-opacity="0.7" />
                                <stop offset="100%" stop-color="#0a2030" stop-opacity="0.9" />
                            </linearGradient>
                            <!-- Glow filters -->
                            <filter id="voltFilter">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur" />
                                <feMerge>
                                    <feMergeNode in="blur" />
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                            <filter id="glowStrong">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="6" />
                            </filter>
                            <filter id="glowSoft">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="3" />
                            </filter>
                            <!-- Chrome rim -->
                            <linearGradient id="rimGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#5a8099" />
                                <stop offset="50%" stop-color="#cce8f5" />
                                <stop offset="100%" stop-color="#3a6080" />
                            </linearGradient>
                        </defs>

                        <!-- ── GROUND GLOW ── -->
                        <ellipse cx="250" cy="325" rx="200" ry="16" fill="rgba(0,245,212,0.08)" class="ground-glow" />
                        <ellipse cx="250" cy="328" rx="150" ry="10" fill="rgba(0,170,255,0.05)" class="ground-glow"
                            style="animation-delay:1s" />

                        <!-- ── GROUND SHADOW ── -->
                        <ellipse cx="250" cy="326" rx="180" ry="12" fill="rgba(0,0,0,0.5)" />

                        <!-- ── MAIN CAR BODY ── -->
                        <!-- Lower body -->
                        <path d="M60 240 Q55 280 70 300 L430 300 Q445 280 440 240 Z" fill="url(#bodyGrad)"
                            stroke="#1a3d54" stroke-width="1.5" />

                        <!-- Side panel detail lines -->
                        <path d="M80 260 L420 260" stroke="#0f2d40" stroke-width="1" />
                        <path d="M75 280 L425 280" stroke="#0f2d40" stroke-width="0.8" />

                        <!-- Door panel -->
                        <path d="M160 244 L160 298 Q200 302 250 300 Q300 302 340 298 L340 244 Z"
                            fill="rgba(14,34,53,0.5)" stroke="#0f2d40" stroke-width="0.8" />

                        <!-- Door handle left -->
                        <rect x="175" y="268" width="40" height="6" rx="3" fill="#1a3d54" stroke="#2a5060"
                            stroke-width="0.8" />
                        <rect x="177" y="269" width="36" height="4" rx="2" fill="#3a7090" />

                        <!-- Door handle right -->
                        <rect x="285" y="268" width="40" height="6" rx="3" fill="#1a3d54" stroke="#2a5060"
                            stroke-width="0.8" />
                        <rect x="287" y="269" width="36" height="4" rx="2" fill="#3a7090" />

                        <!-- ── BODY SIDE TRIM ── -->
                        <!-- Volt trim line (bottom) -->
                        <path d="M80 292 L420 292" stroke="url(#battCell)" stroke-width="1.5" class="energy-flow" />
                        <!-- Subtle volt glow behind trim -->
                        <path d="M80 292 L420 292" stroke="rgba(0,245,212,0.3)" stroke-width="4" filter="url(#glowSoft)"
                            class="energy-flow" />

                        <!-- ── UPPER BODY / ROOF ── -->
                        <path d="M130 240 Q145 180 180 165 L230 155 Q250 152 270 155 L320 165 Q355 180 370 240 Z"
                            fill="url(#roofGrad)" stroke="#1a3d54" stroke-width="1.5" />

                        <!-- ── WINDOWS ── -->
                        <!-- Windshield -->
                        <path d="M175 238 Q182 195 200 178 L250 168 L300 178 Q318 195 325 238 Z" fill="url(#glassGrad)"
                            stroke="#1a4060" stroke-width="1" />
                        <!-- Glass sheen -->
                        <path d="M185 238 Q190 205 205 188 L230 175" fill="none" stroke="rgba(100,180,220,0.2)"
                            stroke-width="2" stroke-linecap="round" />
                        <!-- A-pillar reflection -->
                        <path d="M180 238 Q188 208 205 188" fill="none" stroke="rgba(0,245,212,0.15)"
                            stroke-width="1" />

                        <!-- Rear window -->
                        <path d="M130 240 Q140 215 155 205 L175 200 L175 238 Z" fill="url(#glassGrad)" stroke="#1a4060"
                            stroke-width="0.8" opacity="0.8" />

                        <!-- Front small window -->
                        <path d="M325 238 L325 200 L345 205 Q360 215 370 240 Z" fill="url(#glassGrad)" stroke="#1a4060"
                            stroke-width="0.8" opacity="0.8" />

                        <!-- ── ROOF DETAILS ── -->
                        <path d="M200 155 Q250 148 300 155 Q250 153 200 155" fill="none" stroke="#1a4060"
                            stroke-width="0.8" />
                        <!-- Solar panel hints on roof -->
                        <rect x="210" y="155" width="15" height="20" rx="1" fill="rgba(0,100,150,0.3)" stroke="#0f3050"
                            stroke-width="0.5" />
                        <rect x="230" y="155" width="15" height="20" rx="1" fill="rgba(0,100,150,0.3)" stroke="#0f3050"
                            stroke-width="0.5" />
                        <rect x="255" y="155" width="15" height="20" rx="1" fill="rgba(0,100,150,0.3)" stroke="#0f3050"
                            stroke-width="0.5" />
                        <rect x="275" y="155" width="15" height="20" rx="1" fill="rgba(0,100,150,0.3)" stroke="#0f3050"
                            stroke-width="0.5" />

                        <!-- ── FRONT FASCIA ── -->
                        <path d="M370 238 L450 245 Q462 255 460 280 L425 300 L370 298 Z" fill="url(#bodyGrad)"
                            stroke="#1a3d54" stroke-width="1" />

                        <!-- Front grille (smooth EV style) -->
                        <path d="M430 250 Q455 255 460 268 Q462 280 455 286 L425 290 L425 250 Z" fill="#060e18"
                            stroke="#0f2d40" stroke-width="1" />

                        <!-- EV logo on grille -->
                        <text x="443" y="272" text-anchor="middle" font-family="'Orbitron',monospace" font-size="10"
                            font-weight="700" fill="#00f5d4" letter-spacing="1" opacity="0.8">EV</text>

                        <!-- Front bumper accent -->
                        <path d="M378 296 L428 296" stroke="var(--volt)" stroke-width="2" stroke-linecap="round"
                            class="energy-flow" opacity="0.8" />

                        <!-- ── REAR FASCIA ── -->
                        <path d="M130 238 L50 245 Q38 255 40 280 L75 300 L130 298 Z" fill="url(#bodyGrad)"
                            stroke="#1a3d54" stroke-width="1" />

                        <!-- Rear bumper accent -->
                        <path d="M72 296 L122 296" stroke="var(--arc)" stroke-width="2" stroke-linecap="round"
                            class="energy-flow2" opacity="0.8" />

                        <!-- ── HEADLIGHTS (front) ── -->
                        <!-- Main beam housing -->
                        <path d="M430 250 Q445 252 452 260 L452 252 Q445 248 430 248 Z" fill="#0a1825" stroke="#1a4060"
                            stroke-width="0.8" />
                        <!-- DRL strip -->
                        <path d="M432 252 L450 255" stroke="var(--volt)" stroke-width="2" stroke-linecap="round"
                            class="headlight-l" filter="url(#voltFilter)" />
                        <path d="M433 257 L449 260" stroke="var(--volt)" stroke-width="1.5" stroke-linecap="round"
                            opacity="0.6" class="headlight-l" />
                        <!-- Headlight glow -->
                        <path d="M450 255 L500 240 L500 275 L450 265 Z" fill="rgba(0,245,212,0.04)"
                            class="headlight-l" />

                        <!-- ── TAILLIGHTS (rear) ── -->
                        <path d="M70 250 Q55 252 48 260 L48 252 Q55 248 70 248 Z" fill="#0a1825" stroke="#1a4060"
                            stroke-width="0.8" />
                        <!-- LED strip -->
                        <path d="M68 252 L50 255" stroke="var(--arc)" stroke-width="2" stroke-linecap="round"
                            class="headlight-r" filter="url(#voltFilter)" />
                        <path d="M67 257 L51 260" stroke="var(--arc)" stroke-width="1.5" stroke-linecap="round"
                            opacity="0.6" class="headlight-r" />

                        <!-- Full-width LED rear bar -->
                        <path d="M50 255 Q70 250 130 248" stroke="#00aaff" stroke-width="1.5" class="energy-flow2"
                            filter="url(#glowSoft)" />

                        <!-- Full-width LED front bar -->
                        <path d="M370 248 Q430 250 452 255" stroke="var(--volt)" stroke-width="1.5" class="energy-flow"
                            filter="url(#glowSoft)" />

                        <!-- ── FRONT WHEEL ── -->
                        <!-- Tire -->
                        <circle cx="390" cy="308" r="44" fill="#040c14" stroke="#1a3040" stroke-width="2" />
                        <!-- Tire detail -->
                        <circle cx="390" cy="308" r="38" fill="none" stroke="#0f2030" stroke-width="4" />
                        <!-- Rim -->
                        <g class="wheel-fr">
                            <circle cx="390" cy="308" r="30" fill="url(#wheelGrad)" stroke="#2a5060"
                                stroke-width="1.5" />
                            <!-- Spokes -->
                            <line x1="390" y1="280" x2="390" y2="336" stroke="url(#rimGrad)" stroke-width="2.5"
                                stroke-linecap="round" />
                            <line x1="363" y1="308" x2="417" y2="308" stroke="url(#rimGrad)" stroke-width="2.5"
                                stroke-linecap="round" />
                            <line x1="371" y1="289" x2="409" y2="327" stroke="url(#rimGrad)" stroke-width="2"
                                stroke-linecap="round" />
                            <line x1="371" y1="327" x2="409" y2="289" stroke="url(#rimGrad)" stroke-width="2"
                                stroke-linecap="round" />
                            <!-- Hub -->
                            <circle cx="390" cy="308" r="10" fill="#1a3a50" stroke="#3a7090" stroke-width="1.5" />
                            <circle cx="390" cy="308" r="5" fill="url(#rimGrad)" />
                            <!-- Hub bolt -->
                            <circle cx="390" cy="296" r="2" fill="#3a7090" />
                            <circle cx="390" cy="320" r="2" fill="#3a7090" />
                            <circle cx="378" cy="308" r="2" fill="#3a7090" />
                            <circle cx="402" cy="308" r="2" fill="#3a7090" />
                        </g>
                        <!-- Volt brake caliper -->
                        <path d="M365 290 Q358 308 365 326" stroke="var(--volt)" stroke-width="3" stroke-linecap="round"
                            opacity="0.7" filter="url(#voltFilter)" />

                        <!-- ── REAR WHEEL ── -->
                        <circle cx="110" cy="308" r="44" fill="#040c14" stroke="#1a3040" stroke-width="2" />
                        <circle cx="110" cy="308" r="38" fill="none" stroke="#0f2030" stroke-width="4" />
                        <g class="wheel-fl">
                            <circle cx="110" cy="308" r="30" fill="url(#wheelGrad)" stroke="#2a5060"
                                stroke-width="1.5" />
                            <line x1="110" y1="280" x2="110" y2="336" stroke="url(#rimGrad)" stroke-width="2.5"
                                stroke-linecap="round" />
                            <line x1="83" y1="308" x2="137" y2="308" stroke="url(#rimGrad)" stroke-width="2.5"
                                stroke-linecap="round" />
                            <line x1="91" y1="289" x2="129" y2="327" stroke="url(#rimGrad)" stroke-width="2"
                                stroke-linecap="round" />
                            <line x1="91" y1="327" x2="129" y2="289" stroke="url(#rimGrad)" stroke-width="2"
                                stroke-linecap="round" />
                            <circle cx="110" cy="308" r="10" fill="#1a3a50" stroke="#3a7090" stroke-width="1.5" />
                            <circle cx="110" cy="308" r="5" fill="url(#rimGrad)" />
                            <circle cx="110" cy="296" r="2" fill="#3a7090" />
                            <circle cx="110" cy="320" r="2" fill="#3a7090" />
                            <circle cx="98" cy="308" r="2" fill="#3a7090" />
                            <circle cx="122" cy="308" r="2" fill="#3a7090" />
                        </g>
                        <path d="M135 290 Q142 308 135 326" stroke="var(--arc)" stroke-width="3" stroke-linecap="round"
                            opacity="0.7" filter="url(#voltFilter)" />

                        <!-- ── BATTERY PACK ── -->
                        <rect x="130" y="293" width="240" height="20" rx="5" fill="#060e18" stroke="#1a3d54"
                            stroke-width="1.5" />
                        <!-- Battery cells -->
                        <g class="battery-charge">
                            <rect x="134" y="296" width="36" height="14" rx="3" fill="none" stroke="rgba(0,245,212,0.3)"
                                stroke-width="1" />
                            <rect x="135" y="297" width="34" height="12" rx="2" fill="url(#battCell)"
                                class="battery-cell1" />

                            <rect x="178" y="296" width="36" height="14" rx="3" fill="none" stroke="rgba(0,245,212,0.3)"
                                stroke-width="1" />
                            <rect x="179" y="297" width="34" height="12" rx="2" fill="url(#battCell)"
                                class="battery-cell2" />

                            <rect x="222" y="296" width="36" height="14" rx="3" fill="none" stroke="rgba(0,245,212,0.3)"
                                stroke-width="1" />
                            <rect x="223" y="297" width="34" height="12" rx="2" fill="url(#battCell)"
                                class="battery-cell3" />

                            <rect x="266" y="296" width="36" height="14" rx="3" fill="none" stroke="rgba(0,245,212,0.3)"
                                stroke-width="1" />
                            <rect x="267" y="297" width="34" height="12" rx="2" fill="url(#battCell)"
                                class="battery-cell4" />

                            <rect x="310" y="296" width="36" height="14" rx="3" fill="none" stroke="rgba(0,245,212,0.3)"
                                stroke-width="1" />
                            <rect x="311" y="297" width="34" height="12" rx="2" fill="url(#battCell)"
                                class="battery-cell5" />
                        </g>

                        <!-- Battery glow -->
                        <rect x="130" y="293" width="240" height="20" rx="5" fill="none" stroke="rgba(0,245,212,0.15)"
                            stroke-width="1" filter="url(#glowSoft)" />

                        <!-- ── ELECTRIC MOTOR (under hood area) ── -->
                        <!-- Motor housing -->
                        <ellipse cx="380" cy="268" rx="30" ry="20" fill="#0a1825" stroke="#1a3d54" stroke-width="1.5" />
                        <ellipse cx="380" cy="268" rx="24" ry="15" fill="#060e18" stroke="#1a3d54" stroke-width="1" />
                        <!-- Motor rotor -->
                        <g style="transform-origin:380px 268px; animation:motorSpin 1s linear infinite;">
                            <line x1="380" y1="255" x2="380" y2="281" stroke="#2a5060" stroke-width="3"
                                stroke-linecap="round" />
                            <line x1="367" y1="268" x2="393" y2="268" stroke="#2a5060" stroke-width="3"
                                stroke-linecap="round" />
                            <line x1="371" y1="258" x2="389" y2="278" stroke="#2a5060" stroke-width="2"
                                stroke-linecap="round" />
                            <line x1="371" y1="278" x2="389" y2="258" stroke="#2a5060" stroke-width="2"
                                stroke-linecap="round" />
                        </g>
                        <!-- Motor center -->
                        <circle cx="380" cy="268" r="6" fill="#1a3d54" stroke="var(--volt)" stroke-width="1.5" />
                        <circle cx="380" cy="268" r="3" fill="var(--volt)" opacity="0.8" />

                        <!-- Motor stator glow -->
                        <ellipse cx="380" cy="268" rx="24" ry="15" fill="none" stroke="rgba(0,245,212,0.2)"
                            stroke-width="1.5" filter="url(#glowSoft)" />

                        <!-- ── ENERGY LINES (motor to wheels) ── -->
                        <!-- Front axle energy -->
                        <path d="M350 268 Q390 268 390 285" stroke="var(--volt)" stroke-width="1.5" class="energy-flow"
                            opacity="0.6" stroke-linecap="round" />
                        <!-- Rear axle energy -->
                        <path d="M170 268 Q110 268 110 285" stroke="var(--arc)" stroke-width="1.5" class="energy-flow2"
                            opacity="0.6" stroke-linecap="round" />

                        <!-- Battery to motor -->
                        <path d="M370 296 L380 280" stroke="var(--volt)" stroke-width="1" class="energy-flow"
                            opacity="0.4" stroke-linecap="round" />

                        <!-- ── ARC BOLTS ── -->
                        <g class="arc-bolt" filter="url(#voltFilter)">
                            <!-- Lightning at motor -->
                            <path d="M378 255 L374 262 L379 261 L375 268" fill="none" stroke="var(--charge)"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="376" cy="261" r="3" fill="url(#voltGlow)" />
                        </g>

                        <g class="arc-bolt2" filter="url(#voltFilter)">
                            <path d="M382 255 L386 262 L381 261 L385 268" fill="none" stroke="var(--volt)"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="384" cy="261" r="3" fill="url(#arcGlow)" />
                        </g>

                        <!-- ── HUD DISPLAY (windshield overlay) ── -->
                        <!-- Speed indicator -->
                        <text x="248" y="200" text-anchor="middle" font-family="'Orbitron',monospace" font-size="11"
                            font-weight="700" fill="rgba(0,245,212,0.4)" letter-spacing="2">87 km/h</text>

                        <!-- Range indicator -->
                        <text x="248" y="215" text-anchor="middle" font-family="'Orbitron',monospace" font-size="8"
                            fill="rgba(0,170,255,0.35)" letter-spacing="2">RANGE: 312km</text>

                        <!-- HUD scan line -->
                        <rect x="185" y="185" width="130" height="1" fill="rgba(0,245,212,0.2)" class="hud-scan" />

                        <!-- ── CHARGE PORT ── -->
                        <rect x="350" y="240" width="18" height="12" rx="3" fill="#040c14" stroke="#1a4060"
                            stroke-width="1" />
                        <rect x="352" y="242" width="14" height="8" rx="2" fill="#060e18" stroke="rgba(0,245,212,0.3)"
                            stroke-width="0.8" />
                        <!-- Charge port LED -->
                        <circle cx="359" cy="246" r="2" fill="var(--volt)" opacity="0.8" filter="url(#voltFilter)" />

                        <!-- ── VOLTGARAGE BRANDING ── -->
                        <text x="248" y="246" text-anchor="middle" font-family="'Orbitron',monospace" font-size="9"
                            font-weight="700" letter-spacing="4" fill="rgba(0,245,212,0.15)">VOLTGARAGE</text>

                        <!-- Ambient floor lighting -->
                        <ellipse cx="250" cy="318" rx="160" ry="6" fill="rgba(0,245,212,0.06)" class="ground-glow" />

                        <!-- Corner tech decorations -->
                        <rect x="15" y="140" width="20" height="1" fill="var(--volt)" opacity="0.4" />
                        <rect x="15" y="140" width="1" height="15" fill="var(--volt)" opacity="0.4" />
                        <rect x="465" y="140" width="20" height="1" fill="var(--arc)" opacity="0.4" />
                        <rect x="484" y="140" width="1" height="15" fill="var(--arc)" opacity="0.4" />
                        <rect x="15" y="320" width="20" height="1" fill="var(--volt)" opacity="0.3" />
                        <rect x="15" y="307" width="1" height="13" fill="var(--volt)" opacity="0.3" />
                        <rect x="465" y="320" width="20" height="1" fill="var(--arc)" opacity="0.3" />
                        <rect x="484" y="307" width="1" height="13" fill="var(--arc)" opacity="0.3" />
                    </svg>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════ TICKER ══════════ -->
    <div class="ticker">
        <div class="ticker-track">
            @for($i = 0; $i < 3; $i++)
                <span class="t-item"><i class="bi bi-lightning-charge-fill"></i>Zero Emission<span
                        class="t-dot"></span></span>
                <span class="t-item"><i class="bi bi-battery-charging"></i>Baterai EV Asli<span class="t-dot"></span></span>
                <span class="t-item"><i class="bi bi-shield-fill-check"></i>Garansi Resmi<span class="t-dot"></span></span>
                <span class="t-item"><i class="bi bi-cpu-fill"></i>Smart Inverter<span class="t-dot"></span></span>
                <span class="t-item"><i class="bi bi-ev-station-fill"></i>Fast Charging Parts<span
                        class="t-dot"></span></span>
                <span class="t-item"><i class="bi bi-award-fill"></i>OEM Quality<span class="t-dot"></span></span>
            @endfor
        </div>
    </div>


    <!-- ══════════ TRUST BAND ══════════ -->
    <section class="trust-band">
        <div class="trust-grid">
            <div class="trust-cell">
                <div class="t-icon"><i class="bi bi-battery-full"></i></div>
                <div>
                    <div class="t-ttl">Baterai Genuine</div>
                    <div class="t-dsc">Sel baterai OEM bergaransi kapasitas penuh</div>
                </div>
            </div>
            <div class="trust-cell">
                <div class="t-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div>
                    <div class="t-ttl">Fast Delivery</div>
                    <div class="t-dsc">Pengiriman kilat ke seluruh Indonesia</div>
                </div>
            </div>
            <div class="trust-cell">
                <div class="t-icon"><i class="bi bi-cpu-fill"></i></div>
                <div>
                    <div class="t-ttl">Teknologi Terkini</div>
                    <div class="t-dsc">Komponen EV generasi terbaru, performa optimal</div>
                </div>
            </div>
            <div class="trust-cell">
                <div class="t-icon"><i class="bi bi-headset"></i></div>
                <div>
                    <div class="t-ttl">Support 24/7</div>
                    <div class="t-dsc">Teknisi EV berpengalaman siap membantu</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════ PRODUCTS ══════════ -->
    <main id="main">
        <section id="produk">
            <div class="container-fluid px-4 px-lg-5">

                <div class="s-eye">Katalog EV</div>
                <h2 class="s-ttl">Spare <span>Part</span></h2>
                <p class="s-sub">Komponen listrik terbaik untuk kendaraan masa depan</p>

                @php $uniqueJenis = $produk->pluck('jenis')->unique(); @endphp
                <ul class="flt-list" id="fltList">
                    <li data-filter="*" class="active">Semua</li>
                    @foreach($uniqueJenis as $jenis)
                        <li data-filter=".fj-{{ Str::slug($jenis) }}">{{ $jenis }}</li>
                    @endforeach
                </ul>

                <div class="prod-grid" id="prodGrid">
                    @forelse($produk as $i => $p)
                        <div class="p-card fj-{{ Str::slug($p->jenis) }}">
                            <div class="p-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="p-img">
                                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->nama }}" loading="lazy">
                                <div class="p-over">
                                    <a href="{{ asset('storage/' . $p->image) }}" class="o-btn o-view glightbox"
                                        data-gallery="g{{ $p->kode }}" title="{{ $p->nama }} — {{ $p->tipe }}">
                                        <i class="bi bi-zoom-in"></i> Foto
                                    </a>
                                    @if(Auth::check() && Auth::user()->role == 'Guest')
                                        <button class="o-btn o-buy" data-bs-toggle="modal" data-bs-target="#m{{ $p->kode }}">
                                            <i class="bi bi-cart-plus-fill"></i> Beli
                                        </button>
                                    @endif
                                    @if(Auth::check() && Auth::user()->role == 'Guest')
                                    <button class="o-btn"
                                        style="background:rgba(0,245,212,0.12);border:1px solid rgba(0,245,212,0.35);color:var(--volt);"
                                        data-add-cart data-id="{{ $p->id }}" data-nama="{{ addslashes($p->nama) }}"
                                        data-tipe="{{ addslashes($p->tipe) }}" data-jenis="{{ addslashes($p->jenis) }}"
                                        data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}"
                                        data-img="{{ asset('storage/' . $p->image) }}">
                                        <i class="bi bi-bag-plus-fill"></i> Keranjang
                                    </button>
                                     @endif
                                </div>
                            </div>
                            <div class="p-body">
                                <div class="p-jenis"><i class="bi bi-lightning-charge-fill me-1"></i>{{ $p->jenis }}</div>
                                <div class="p-name">{{ $p->nama }}</div>
                                <div class="p-tipe">{{ $p->tipe }}</div>
                                <div class="p-foot">
                                    <div class="p-price">
                                        <small>Harga</small>
                                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                                    </div>
                                    @php
                                        $sc = $p->stok <= 0 ? 'out' : ($p->stok <= 5 ? 'low' : 'ok');
                                        $sl = $p->stok <= 0 ? 'Habis' : ($p->stok <= 5 ? 'Sisa ' . $p->stok : 'Stok ' . $p->stok);
                                    @endphp
                                    <div class="p-stk s-{{ $sc }}"><span class="sdot"></span>{{ $sl }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL -->
                        <div class="modal fade" id="m{{ $p->kode }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <svg width="18" height="18" viewBox="0 0 16 16" fill="none"
                                                style="margin-right:8px;vertical-align:-3px">
                                                <path d="M9 2L4 9h4l-2 5 7-7H9L11 2z" fill="var(--volt)" opacity="0.9" />
                                            </svg>
                                            Order EV Part
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/' . $p->image) }}" class="m-img" alt="{{ $p->nama }}">
                                        <div class="m-info">
                                            <div class="m-row"><span class="m-lbl">Nama Part</span><span
                                                    class="m-val">{{ $p->nama }}</span></div>
                                            <div class="m-row"><span class="m-lbl">Tipe</span><span
                                                    class="m-val">{{ $p->tipe }}</span></div>
                                            <div class="m-row"><span class="m-lbl">Kategori</span><span
                                                    class="m-val">{{ $p->jenis }}</span></div>
                                            <div class="m-row"><span class="m-lbl">Harga / Unit</span><span
                                                    class="m-val heat">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="m-row"><span class="m-lbl">Stok</span><span
                                                    class="m-val">{{ $p->stok }} unit</span></div>
                                        </div>
                                        <div class="m-form">
                                            <form action="/pembelian/storeinput" method="post">
                                                @csrf
                                                <input type="hidden" name="kodeproduk" value="{{ $p->id }}">
                                                <input type="hidden" name="harga" value="{{ $p->harga }}">
                                                <label class="f-lbl">Jumlah Pembelian</label>
                                                <input type="number" name="banyak" required class="f-inp inp-qty" min="1"
                                                    max="{{ $p->stok }}" placeholder="Masukkan jumlah unit..."
                                                    data-harga="{{ $p->harga }}" data-kode="{{ $p->kode }}">
                                                <div class="total-wrap" id="tw{{ $p->kode }}">
                                                    <div class="total-lbl">Total Pembayaran</div>
                                                    <div class="total-val" id="tv{{ $p->kode }}">Rp 0</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="m-cancel"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="button" class="m-cancel"
                                                        style="color:var(--volt);border-color:rgba(0,245,212,0.3);"
                                                        data-bs-dismiss="modal" data-add-cart data-id="{{ $p->id }}"
                                                        data-nama="{{ addslashes($p->nama) }}"
                                                        data-tipe="{{ addslashes($p->tipe) }}"
                                                        data-jenis="{{ addslashes($p->jenis) }}"
                                                        data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}"
                                                        data-img="{{ asset('storage/' . $p->image) }}">
                                                        <i class="bi bi-bag-plus-fill"></i> + Keranjang
                                                    </button>
                                                    <button type="submit" class="cta-volt"
                                                        style="font-size:10px;padding:10px 22px;">
                                                        <i class="bi bi-check-circle-fill"></i> Konfirmasi
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            style="grid-column:1/-1;padding:80px;text-align:center;border-right:1px solid var(--border);border-bottom:1px solid var(--border);">
                            <i class="bi bi-battery-charging"
                                style="font-size:56px;color:var(--border);display:block;margin-bottom:14px;"></i>
                            <p style="font-family:var(--font-display);font-size:22px;color:var(--dim);letter-spacing:4px;">
                                BELUM ADA PRODUK</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>


    <!-- ══════════ FOOTER ══════════ -->
    <footer id="footer">
        <div class="footer-top">
            <div>
                <div class="f-brand-name"><em>VOLT</em>GARAGE</div>
                <div class="f-brand-tag">Electric Vehicle Parts Indonesia</div>
                <p class="f-brand-desc">Solusi terlengkap untuk spare part kendaraan listrik. Teknologi masa depan,
                    tersedia hari ini.</p>
            </div>
            <nav class="f-nav">
                <a href="/">Home</a>
                <a href="#produk">Produk</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </nav>
        </div>
        <div class="footer-btm">
            <div class="f-copy">
                &copy; {{ date('Y') }} <strong>VoltGarage</strong>. All Rights Reserved.
                &nbsp;·&nbsp; Designed by <a href="https://rpl-tamsis2jakarta.webnode.page/">RPL Tamsis 2</a>
            </div>
            <div
                style="font-family:var(--font-display);font-size:9px;letter-spacing:4px;text-transform:uppercase;color:var(--dim);">
                POWERED BY ELECTRICITY &nbsp;
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="vertical-align:-2px">
                    <path d="M9 2L4 9h4l-2 5 7-7H9L11 2z" fill="var(--volt)" opacity="0.8" />
                </svg>
            </div>
        </div>
    </footer>

    <!-- ══════════ CART FAB ══════════ -->
    <button class="cart-fab" onclick="toggleCart()" title="Keranjang Belanja" id="cartFab">
        <i class="bi bi-bag-fill"></i>
        <span class="cart-badge" id="cartBadge">0</span>
    </button>

    <!-- ══════════ CART OVERLAY ══════════ -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

    <!-- ══════════ CART DRAWER ══════════ -->
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-head">
            <div class="cart-head-title">
                <i class="bi bi-bag-fill"></i> Keranjang
                <span style="font-size:10px;color:var(--grey);font-family:var(--font-title);"
                    id="drawerItemCount"></span>
            </div>
            <button class="cart-close-btn" onclick="toggleCart()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="cart-body" id="cartBody">
            <!-- Filled by JS -->
        </div>

        <div class="cart-foot">
            <div class="cart-subtotal">
                <span class="cart-subtotal-lbl">Total Belanja</span>
                <span class="cart-subtotal-val" id="cartTotal">Rp 0</span>
            </div>
            <div class="cart-item-count" id="cartItemCount">0 produk</div>
            <hr class="cart-separator">
            <button class="btn-checkout" id="btnCheckout" onclick="checkoutCart()" disabled>
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M9 2L4 9h4l-2 5 7-7H9L11 2z" />
                </svg>
                Checkout Sekarang
            </button>
            <button class="btn-clear-cart" onclick="clearCart()">
                <i class="bi bi-trash3"></i> Kosongkan Keranjang
            </button>
        </div>
    </div>

    <!-- ══════════ CART TOAST ══════════ -->
    <div class="cart-toast" id="cartToast">
        <i class="bi bi-check-circle-fill"></i>
        <span id="cartToastMsg">Ditambahkan ke keranjang!</span>
    </div>

    <a href="#" class="btt" id="bttBtn"><i class="bi bi-arrow-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <script>
        GLightbox({ selector: '.glightbox' });

        const bttBtn = document.getElementById('bttBtn');
        window.addEventListener('scroll', () => bttBtn.classList.toggle('on', window.scrollY > 400));

        const fltList = document.getElementById('fltList');
        const allCards = document.querySelectorAll('.p-card');


        fltList.querySelectorAll('li').forEach(btn => {
            btn.addEventListener('click', function () {
                fltList.querySelectorAll('li').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const f = this.dataset.filter;
                let vi = 0;
                allCards.forEach(card => {
                    const show = f === '*' || card.classList.contains(f.replace('.', ''));
                    card.style.display = show ? 'flex' : 'none';
                    if (show) {
                        card.style.animation = 'none';
                        void card.offsetWidth;
                        card.style.animation = `cardIn 0.45s ${vi * 0.05}s ease both`;
                        vi++;
                    }
                });
            });
        });

        document.querySelectorAll('.inp-qty').forEach(inp => {
            inp.addEventListener('input', function () {
                const harga = parseInt(this.dataset.harga) || 0;
                const qty = parseInt(this.value) || 0;
                const kode = this.dataset.kode;
                const tw = document.getElementById('tw' + kode);
                const tv = document.getElementById('tv' + kode);
                if (tw && tv) {
                    tv.textContent = 'Rp ' + (harga * qty).toLocaleString('id-ID');
                    tw.classList.toggle('on', qty > 0);
                }
            });
        });

        const io = new IntersectionObserver(entries => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    e.target.style.animation = `cardIn 0.5s ${i * 0.06}s ease both`;
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.08 });
        allCards.forEach(c => io.observe(c));

        /* ══════════════════════════════════
           CART SYSTEM
        ══════════════════════════════════ */
        const CART_KEY = 'voltara_cart_{{ Auth::id() }}';
            let cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');

            function saveCart() {
                localStorage.setItem(CART_KEY, JSON.stringify(cart));
                updateCartUI();
            }

        function addToCart(id, nama, tipe, jenis, harga, stok, imgUrl) {
            const existing = cart.find(i => i.id === id);
            if (existing) {
                if (existing.qty < stok) {
                    existing.qty++;
                    showToast(`+1 ${nama}`);
                } else {
                    showToast(`Stok maksimal: ${stok}`);
                    return;
                }
            } else {
                cart.push({ id, nama, tipe, jenis, harga, stok, imgUrl, qty: 1 });
                showToast(`${nama} ditambahkan!`);
            }
            saveCart();
            updateCartUI();
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            saveCart();
            updateCartUI();
            renderCartBody();
        }

        function changeQty(id, delta) {
            const item = cart.find(i => i.id === id);
            if (!item) return;
            item.qty = Math.max(1, Math.min(item.stok, item.qty + delta));
            saveCart();
            updateCartUI();
            renderCartBody();
        }

        function clearCart() {
            if (cart.length === 0) return;
            cart = [];
            saveCart();
            updateCartUI();
            renderCartBody();
        }

        function updateCartUI() {
            const totalQty = cart.reduce((s, i) => s + i.qty, 0);
            const totalPrice = cart.reduce((s, i) => s + (i.harga * i.qty), 0);
            const itemCount = cart.length;

            // Badge
            const badge = document.getElementById('cartBadge');
            const navBadge = document.getElementById('cartNavBadge');
            if (totalQty > 0) {
                badge.textContent = totalQty > 99 ? '99+' : totalQty;
                badge.classList.add('show');
                navBadge.textContent = totalQty > 99 ? '99+' : totalQty;
                navBadge.classList.add('show');
            } else {
                badge.classList.remove('show');
                navBadge.classList.remove('show');
            }

            // Total & count
            document.getElementById('cartTotal').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
            document.getElementById('cartItemCount').textContent = itemCount + ' produk · ' + totalQty + ' unit';
            document.getElementById('drawerItemCount').textContent = totalQty > 0 ? `(${totalQty})` : '';

            // Checkout button
            document.getElementById('btnCheckout').disabled = (cart.length === 0);
        }

        function renderCartBody() {
            const body = document.getElementById('cartBody');
            if (cart.length === 0) {
                body.innerHTML = `
                <div class="cart-empty">
                    <i class="bi bi-battery"></i>
                    <p>Keranjang Kosong</p>
                    <span>Tambahkan spare part EV favorit Anda ke keranjang</span>
                </div>`;
                return;
            }

            body.innerHTML = cart.map(item => `
            <div class="cart-item" id="ci-${item.id}">
                <img src="${item.imgUrl}" alt="${item.nama}" class="cart-item-img">
                <div class="cart-item-info">
                    <div class="cart-item-cat"><i class="bi bi-lightning-charge-fill"></i> ${item.jenis}</div>
                    <div class="cart-item-name">${item.nama}</div>
                    <div class="cart-item-tipe">${item.tipe}</div>
                    <div class="cart-qty-row">
                        <button class="qty-btn" onclick="changeQty('${item.id}', -1)">−</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" onclick="changeQty('${item.id}', 1)">+</button>
                    </div>
                </div>
                <div>
                    <div class="cart-item-price">
                        <small>Subtotal</small>
                        Rp ${(item.harga * item.qty).toLocaleString('id-ID')}
                    </div>
                    <button class="cart-del-btn" onclick="removeFromCart('${item.id}')">
                        <i class="bi bi-trash3-fill"></i> Hapus
                    </button>
                </div>
            </div>
        `).join('');
        }

        function toggleCart() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartOverlay');
            const isOpen = drawer.classList.contains('open');
            if (!isOpen) renderCartBody();
            drawer.classList.toggle('open');
            overlay.classList.toggle('show');
            document.body.style.overflow = isOpen ? '' : 'hidden';
        }

        let toastTimer;
        function showToast(msg) {
            const toast = document.getElementById('cartToast');
            document.getElementById('cartToastMsg').textContent = msg;
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.remove('show'), 2500);
        }

        // Antrian submit ke /pembelian/storeinput satu per satu
        let checkoutQueue = [];
        let checkoutIndex = 0;

        function checkoutCart() {
            if (cart.length === 0) return;

            const total = cart.reduce((s, i) => s + (i.harga * i.qty), 0);
            const lines = cart.map(i =>
                `• ${i.nama} × ${i.qty}  →  Rp ${(i.harga * i.qty).toLocaleString('id-ID')}`
            ).join('\n');
            const msg = `⚡ KONFIRMASI PESANAN\n\n${lines}\n\n${'─'.repeat(36)}\nTotal  :  Rp ${total.toLocaleString('id-ID')}\n\nLanjutkan ke pembelian?`;

            if (!confirm(msg)) return;

            // Simpan antrian item, lalu mulai submit item pertama
            checkoutQueue = [...cart];
            checkoutIndex = 0;
            clearCart();            // Kosongkan keranjang
            submitNextItem();
        }

        function submitNextItem() {
            if (checkoutIndex >= checkoutQueue.length) return; // selesai

            const item = checkoutQueue[checkoutIndex];
            checkoutIndex++;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                || document.querySelector('input[name="_token"]')?.value
                || '';

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/pembelian/storeinput';
            form.style.display = 'none';

            form.innerHTML = `
            <input type="hidden" name="_token"     value="${csrfToken}">
            <input type="hidden" name="kodeproduk" value="${item.id}">
            <input type="hidden" name="harga"      value="${item.harga}">
            <input type="hidden" name="banyak"     value="${item.qty}">
            <input type="hidden" name="_cart_index"  value="${checkoutIndex}">
            <input type="hidden" name="_cart_total"  value="${checkoutQueue.length}">
        `;

            document.body.appendChild(form);
            form.submit();
            // Laravel akan memproses lalu redirect; item berikutnya diproses setelah redirect balik
            // Jika backend redirect ke halaman yang sama, sisa antrian dilanjutkan via sessionStorage
        }

        // Saat halaman load, cek apakah ada antrian yang belum selesai (setelah redirect dari Laravel)
        (function resumeCheckoutIfNeeded() {
            const saved = sessionStorage.getItem('ev_checkout_queue');
            if (!saved) return;
            try {
                const state = JSON.parse(saved);
                if (!state.queue || state.index >= state.queue.length) {
                    sessionStorage.removeItem('ev_checkout_queue');
                    return;
                }
                checkoutQueue = state.queue;
                checkoutIndex = state.index;
                sessionStorage.removeItem('ev_checkout_queue');
                // Tunda sedikit agar DOM siap
                setTimeout(submitNextItem, 200);
            } catch (e) {
                sessionStorage.removeItem('ev_checkout_queue');
            }
        })();

        // Simpan state sebelum form submit (untuk resume setelah redirect)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.action && form.action.includes('/pembelian/storeinput') && checkoutQueue.length > 0) {
                if (checkoutIndex < checkoutQueue.length) {
                    sessionStorage.setItem('ev_checkout_queue', JSON.stringify({
                        queue: checkoutQueue,
                        index: checkoutIndex
                    }));
                }
            }
        });

        // Patch "Beli" buttons in product overlay to also add to cart
        document.querySelectorAll('.o-buy[data-bs-toggle="modal"]').forEach(btn => {
            // Keep original modal open, but ALSO add add-to-cart via data attrs on card
        });

        // Attach "Tambah ke Keranjang" buttons added via data attribute approach
        document.querySelectorAll('[data-add-cart]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const d = this.dataset;
                addToCart(d.id, d.nama, d.tipe, d.jenis, parseInt(d.harga), parseInt(d.stok), d.img);
            });
        });

        // Init
        updateCartUI();
    </script>

</body>

</html>
