<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wareed — Blood Donation Network</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --blood:      #C0392B;
    --blood-dark: #7B241C;
    --blood-deep: #1a0503;
    --cream:      #FAF7F2;
    --warm-gray:  #8B7B74;
    --gold:       #C9A84C;
    --white:      #FFFFFF;
    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
    --font-mono:    'DM Mono', monospace;
  }

  html { scroll-behavior: smooth; }

  body {
    background: var(--blood-deep);
    color: var(--cream);
    font-family: var(--font-body);
    font-weight: 300;
    overflow-x: hidden;
    cursor: none;
  }

  /* ── Custom cursor ── */
  .cursor {
    position: fixed;
    width: 10px; height: 10px;
    background: var(--blood);
    border-radius: 50%;
    pointer-events: none;
    z-index: 9999;
    transform: translate(-50%,-50%);
    transition: transform 0.1s ease, width 0.3s ease, height 0.3s ease, background 0.3s ease;
    mix-blend-mode: screen;
  }
  .cursor-ring {
    position: fixed;
    width: 36px; height: 36px;
    border: 1px solid rgba(192,57,43,0.4);
    border-radius: 50%;
    pointer-events: none;
    z-index: 9998;
    transform: translate(-50%,-50%);
    transition: transform 0.18s ease, width 0.3s ease, height 0.3s ease;
  }
  body:hover .cursor { opacity: 1; }

  /* ── Grain overlay ── */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
    opacity: 0.035;
    pointer-events: none;
    z-index: 1000;
  }

  /* ── NAV ── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 28px 60px;
    background: linear-gradient(to bottom, rgba(26,5,3,0.95) 0%, transparent 100%);
  }

  .nav-logo {
    display: flex;
    align-items: baseline;
    gap: 10px;
  }

  .nav-logo-text {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 600;
    letter-spacing: 0.08em;
    color: var(--cream);
  }

  .nav-logo-dot {
    width: 7px; height: 7px;
    background: var(--blood);
    border-radius: 50%;
    display: inline-block;
    margin-bottom: 4px;
    animation: pulse-dot 2s ease-in-out infinite;
  }

  @keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 40px;
    list-style: none;
  }

  .nav-links a {
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--warm-gray);
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .nav-links a:hover { color: var(--cream); }

  .nav-cta {
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--blood-deep) !important;
    background: var(--cream);
    padding: 10px 22px;
    border-radius: 2px;
    text-decoration: none;
    transition: background 0.3s ease, color 0.3s ease !important;
  }

  .nav-cta:hover {
    background: var(--blood) !important;
    color: var(--white) !important;
  }

  /* ── HERO ── */
  .hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 160px 60px 100px;
    overflow: hidden;
  }

  /* Blood drip background art */
  .hero-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
    overflow: hidden;
  }

  .hero-bg-circle {
    position: absolute;
    right: -80px;
    top: 50%;
    transform: translateY(-50%);
    width: 700px;
    height: 700px;
    border-radius: 50%;
    background: radial-gradient(circle at 40% 40%,
      rgba(192,57,43,0.18) 0%,
      rgba(123,36,28,0.12) 40%,
      transparent 70%
    );
    animation: breathe 8s ease-in-out infinite;
  }

  .hero-bg-lines {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(192,57,43,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(192,57,43,0.04) 1px, transparent 1px);
    background-size: 80px 80px;
  }

  .hero-drip {
    position: absolute;
    top: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--blood) 0%, transparent 100%);
    animation: drip linear infinite;
    opacity: 0;
  }

  @keyframes drip {
    0%   { opacity: 0; transform: translateY(-100%); }
    10%  { opacity: 0.6; }
    90%  { opacity: 0.2; }
    100% { opacity: 0; transform: translateY(100vh); }
  }

  @keyframes breathe {
    0%, 100% { transform: translateY(-50%) scale(1); opacity: 1; }
    50%       { transform: translateY(-50%) scale(1.05); opacity: 0.8; }
  }

  /* Hero content */
  .hero-content {
    position: relative;
    z-index: 10;
    max-width: 780px;
  }

  .hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--blood);
    margin-bottom: 40px;
    opacity: 0;
    animation: fade-up 0.8s ease forwards 0.2s;
  }

  .hero-tag::before {
    content: '';
    display: block;
    width: 30px;
    height: 1px;
    background: var(--blood);
  }

  .hero-title {
    font-family: var(--font-display);
    font-size: clamp(72px, 9vw, 130px);
    font-weight: 300;
    line-height: 0.92;
    letter-spacing: -0.02em;
    color: var(--cream);
    margin-bottom: 48px;
    opacity: 0;
    animation: fade-up 1s ease forwards 0.4s;
  }

  .hero-title em {
    font-style: italic;
    color: var(--blood);
  }

  .hero-title .hero-title-line2 {
    display: block;
    padding-left: 120px;
  }

  .hero-subtitle {
    font-size: 16px;
    font-weight: 300;
    line-height: 1.8;
    color: var(--warm-gray);
    max-width: 460px;
    margin-bottom: 64px;
    opacity: 0;
    animation: fade-up 1s ease forwards 0.6s;
  }

  .hero-actions {
    display: flex;
    align-items: center;
    gap: 32px;
    opacity: 0;
    animation: fade-up 1s ease forwards 0.8s;
  }

  .btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: var(--blood);
    color: var(--white);
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    text-decoration: none;
    padding: 18px 36px;
    border-radius: 2px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 0 0 0 rgba(192,57,43,0);
  }

  .btn-hero-primary:hover {
    background: var(--blood-dark);
    transform: translateY(-2px);
    box-shadow: 0 20px 60px rgba(192,57,43,0.25);
  }

  .btn-hero-primary svg {
    transition: transform 0.3s ease;
  }

  .btn-hero-primary:hover svg { transform: translateX(4px); }

  .btn-hero-ghost {
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--warm-gray);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
    border-bottom: 1px solid transparent;
    padding-bottom: 2px;
  }

  .btn-hero-ghost:hover {
    color: var(--cream);
    border-bottom-color: var(--cream);
  }

  /* Hero stats bar */
  .hero-stats {
    position: absolute;
    bottom: 60px;
    left: 60px;
    right: 60px;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0;
    border-top: 1px solid rgba(139,123,116,0.15);
    padding-top: 32px;
    opacity: 0;
    animation: fade-up 1s ease forwards 1.2s;
  }

  .hero-stat {
    flex: 1;
    padding-right: 40px;
  }

  .hero-stat + .hero-stat {
    padding-left: 40px;
    border-left: 1px solid rgba(139,123,116,0.15);
  }

  .hero-stat-number {
    font-family: var(--font-display);
    font-size: 42px;
    font-weight: 300;
    color: var(--cream);
    line-height: 1;
    margin-bottom: 6px;
  }

  .hero-stat-number span {
    color: var(--blood);
  }

  .hero-stat-label {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--warm-gray);
  }

  /* Blood drop floating art */
  .blood-drop {
    position: absolute;
    right: 8%;
    top: 50%;
    transform: translateY(-55%);
    z-index: 5;
    opacity: 0;
    animation: fade-in 1.5s ease forwards 1s;
  }

  @keyframes fade-in {
    to { opacity: 1; }
  }

  .blood-drop svg {
    width: 340px;
    height: auto;
    animation: float 6s ease-in-out infinite;
    filter: drop-shadow(0 40px 80px rgba(192,57,43,0.3));
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33%       { transform: translateY(-16px) rotate(1deg); }
    66%       { transform: translateY(-8px) rotate(-0.5deg); }
  }

  @keyframes fade-up {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── HOW IT WORKS ── */
  .section-how {
    position: relative;
    padding: 140px 60px;
    background: var(--cream);
    color: var(--blood-deep);
    overflow: hidden;
  }

  .section-how::before {
    content: 'HOW';
    position: absolute;
    top: -20px;
    left: -20px;
    font-family: var(--font-display);
    font-size: 280px;
    font-weight: 600;
    color: rgba(192,57,43,0.04);
    line-height: 1;
    pointer-events: none;
    user-select: none;
  }

  .section-label {
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--blood);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .section-label::after {
    content: '';
    display: block;
    flex: 1;
    max-width: 40px;
    height: 1px;
    background: var(--blood);
  }

  .section-title {
    font-family: var(--font-display);
    font-size: clamp(42px, 5vw, 72px);
    font-weight: 300;
    line-height: 1.05;
    letter-spacing: -0.02em;
    color: var(--blood-deep);
    margin-bottom: 80px;
    max-width: 600px;
  }

  .steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    background: rgba(192,57,43,0.08);
  }

  .step {
    background: var(--cream);
    padding: 48px 40px;
    position: relative;
    transition: background 0.4s ease;
  }

  .step:hover { background: var(--blood-deep); }
  .step:hover .step-number { color: rgba(192,57,43,0.2); }
  .step:hover .step-title { color: var(--cream); }
  .step:hover .step-desc { color: var(--warm-gray); }
  .step:hover .step-icon { background: var(--blood); color: var(--white); }

  .step-number {
    font-family: var(--font-display);
    font-size: 96px;
    font-weight: 300;
    color: rgba(192,57,43,0.1);
    line-height: 1;
    margin-bottom: 24px;
    transition: color 0.4s ease;
  }

  .step-icon {
    width: 48px;
    height: 48px;
    background: rgba(192,57,43,0.1);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 20px;
    transition: background 0.4s ease, color 0.4s ease;
  }

  .step-title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 400;
    color: var(--blood-deep);
    margin-bottom: 14px;
    transition: color 0.4s ease;
  }

  .step-desc {
    font-size: 14px;
    line-height: 1.75;
    color: var(--warm-gray);
    transition: color 0.4s ease;
  }

  /* ── BLOOD TYPES ── */
  .section-types {
    padding: 140px 60px;
    background: var(--blood-deep);
    position: relative;
    overflow: hidden;
  }

  .section-types::after {
    content: '';
    position: absolute;
    right: -200px;
    top: 50%;
    transform: translateY(-50%);
    width: 600px;
    height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(192,57,43,0.12) 0%, transparent 70%);
    pointer-events: none;
  }

  .types-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 64px;
    gap: 60px;
  }

  .types-header .section-title { margin-bottom: 0; color: var(--cream); }

  .types-desc {
    font-size: 14px;
    line-height: 1.8;
    color: var(--warm-gray);
    max-width: 340px;
    flex-shrink: 0;
  }

  .blood-types-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 2px;
  }

  .blood-type-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    padding: 28px 16px;
    text-align: center;
    border-radius: 4px;
    transition: background 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    cursor: default;
  }

  .blood-type-card:hover {
    background: var(--blood);
    border-color: var(--blood);
    transform: translateY(-6px);
  }

  .blood-type-card:hover .bt-label { color: var(--white); }
  .blood-type-card:hover .bt-desc  { color: rgba(255,255,255,0.7); }
  .blood-type-card:hover .bt-universal { display: block; }

  .bt-label {
    font-family: var(--font-display);
    font-size: 36px;
    font-weight: 300;
    color: var(--cream);
    line-height: 1;
    margin-bottom: 8px;
  }

  .bt-desc {
    font-family: var(--font-mono);
    font-size: 9px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--warm-gray);
    line-height: 1.4;
  }

  .bt-universal {
    display: none;
    margin-top: 8px;
    font-family: var(--font-mono);
    font-size: 9px;
    color: rgba(255,255,255,0.9);
    letter-spacing: 0.1em;
    text-transform: uppercase;
  }

  /* ── TRUST ── */
  .section-trust {
    padding: 140px 60px;
    background: var(--cream);
    color: var(--blood-deep);
    position: relative;
    overflow: hidden;
  }

  .trust-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 100px;
    align-items: center;
  }

  .trust-left .section-title { color: var(--blood-deep); margin-bottom: 24px; }

  .trust-tagline {
    font-size: 15px;
    line-height: 1.9;
    color: var(--warm-gray);
    margin-bottom: 48px;
    max-width: 420px;
  }

  .trust-badges {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .trust-badge {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
    background: rgba(26,5,3,0.04);
    border-radius: 4px;
    transition: background 0.3s ease;
  }

  .trust-badge:hover { background: rgba(26,5,3,0.07); }

  .trust-badge-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }

  .trust-badge-icon.new     { background: rgba(139,123,116,0.15); }
  .trust-badge-icon.active  { background: rgba(37,99,235,0.12); }
  .trust-badge-icon.trusted { background: rgba(22,163,74,0.12); }
  .trust-badge-icon.hero    { background: rgba(201,168,76,0.15); }

  .trust-badge-info { flex: 1; }

  .trust-badge-name {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 400;
    color: var(--blood-deep);
    margin-bottom: 2px;
  }

  .trust-badge-req {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--warm-gray);
  }

  .trust-right {
    position: relative;
  }

  .trust-visual {
    position: relative;
    background: var(--blood-deep);
    border-radius: 16px;
    padding: 48px 40px;
    overflow: hidden;
  }

  .trust-visual::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(192,57,43,0.25) 0%, transparent 70%);
  }

  .trust-visual-title {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--blood);
    margin-bottom: 32px;
  }

  .trust-stat-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 16px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }

  .trust-stat-row:last-child { border-bottom: none; }

  .trust-stat-key {
    font-size: 13px;
    color: var(--warm-gray);
  }

  .trust-stat-val {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 300;
    color: var(--cream);
  }

  .trust-stat-val span { color: var(--blood); }

  /* ── CTA ── */
  .section-cta {
    padding: 160px 60px;
    background: var(--blood);
    position: relative;
    overflow: hidden;
    text-align: center;
  }

  .section-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 20% 50%, rgba(0,0,0,0.2) 0%, transparent 60%),
      radial-gradient(circle at 80% 50%, rgba(0,0,0,0.15) 0%, transparent 60%);
  }

  .cta-content { position: relative; z-index: 1; }

  .cta-overline {
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
    margin-bottom: 28px;
  }

  .cta-title {
    font-family: var(--font-display);
    font-size: clamp(52px, 6vw, 96px);
    font-weight: 300;
    line-height: 1;
    letter-spacing: -0.02em;
    color: var(--white);
    margin-bottom: 20px;
  }

  .cta-title em {
    font-style: italic;
    opacity: 0.7;
  }

  .cta-sub {
    font-size: 15px;
    color: rgba(255,255,255,0.65);
    margin-bottom: 60px;
    line-height: 1.7;
  }

  .cta-buttons {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
  }

  .btn-cta-white {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--white);
    color: var(--blood);
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    text-decoration: none;
    padding: 18px 40px;
    border-radius: 2px;
    font-weight: 500;
    transition: background 0.3s, transform 0.3s;
  }

  .btn-cta-white:hover {
    background: var(--blood-deep);
    color: var(--white);
    transform: translateY(-2px);
  }

  .btn-cta-outline {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    color: var(--white);
    border: 1px solid rgba(255,255,255,0.4);
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    text-decoration: none;
    padding: 18px 40px;
    border-radius: 2px;
    transition: background 0.3s, border-color 0.3s, transform 0.3s;
  }

  .btn-cta-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.8);
    transform: translateY(-2px);
  }

  /* ── FOOTER ── */
  footer {
    background: #0D0201;
    padding: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .footer-logo {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 300;
    color: var(--warm-gray);
    letter-spacing: 0.06em;
  }

  .footer-copy {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(139,123,116,0.4);
  }

  .footer-links {
    display: flex;
    gap: 32px;
    list-style: none;
  }

  .footer-links a {
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(139,123,116,0.5);
    text-decoration: none;
    transition: color 0.3s;
  }

  .footer-links a:hover { color: var(--warm-gray); }

  /* ── Scroll reveal ── */
  .reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }

  .reveal.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* ── Mobile ── */
  @media (max-width: 900px) {
    nav { padding: 24px 24px; }
    .nav-links { display: none; }
    .hero { padding: 120px 24px 180px; }
    .hero-stats { left: 24px; right: 24px; bottom: 40px; flex-wrap: wrap; gap: 24px; }
    .hero-stat + .hero-stat { border-left: none; padding-left: 0; }
    .blood-drop { display: none; }
    .section-how, .section-types, .section-trust, .section-cta { padding: 80px 24px; }
    .steps-grid { grid-template-columns: 1fr; }
    .blood-types-grid { grid-template-columns: repeat(4, 1fr); }
    .trust-grid { grid-template-columns: 1fr; gap: 48px; }
    .types-header { flex-direction: column; gap: 24px; }
    footer { flex-direction: column; gap: 24px; text-align: center; }
  }
</style>
</head>
<body>

<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursor-ring"></div>

<!-- NAV -->
<nav>
  <div class="nav-logo">
    <span class="nav-logo-text">Wareed</span>
    <span class="nav-logo-dot"></span>
  </div>
  <ul class="nav-links">
    <li><a href="#how">How it works</a></li>
    <li><a href="#blood-types">Blood types</a></li>
    <li><a href="#trust">Trust</a></li>
    <li><a href="{{ route('hospital.login') }}">Hospitals</a></li>
    <li><a href="{{ route('login') }}" class="nav-cta">Donate now</a></li>
  </ul>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg">
    <div class="hero-bg-lines"></div>
    <div class="hero-bg-circle"></div>
  </div>

  <div class="hero-content">
    <div class="hero-tag">Blood Donation Network · Egypt</div>

    <h1 class="hero-title">
      Every<br>
      <em>second</em>
      <span class="hero-title-line2">matters.</span>
    </h1>

    <p class="hero-subtitle">
      Wareed connects hospitals to compatible donors instantly.
      When an emergency strikes, the right blood reaches the right
      place — in seconds, not hours.
    </p>

    <div class="hero-actions">
      <a href="{{ route('register') }}" class="btn-hero-primary">
        Become a donor
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <a href="{{ route('hospital.register') }}" class="btn-hero-ghost">
        Register a hospital
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
          <path d="M2 7h10M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>
  </div>

  <!-- Floating blood drop SVG -->
  <div class="blood-drop">
    <svg viewBox="0 0 280 380" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <radialGradient id="dropGrad" cx="40%" cy="30%" r="65%">
          <stop offset="0%" stop-color="#E74C3C" stop-opacity="0.9"/>
          <stop offset="60%" stop-color="#C0392B" stop-opacity="0.85"/>
          <stop offset="100%" stop-color="#7B241C" stop-opacity="0.95"/>
        </radialGradient>
        <radialGradient id="shineGrad" cx="35%" cy="25%" r="40%">
          <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.15"/>
          <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
        </radialGradient>
      </defs>
      <!-- Drop shape -->
      <path d="M140 20 C140 20, 30 140, 30 230 C30 305, 78 360, 140 360 C202 360, 250 305, 250 230 C250 140, 140 20, 140 20Z"
        fill="url(#dropGrad)"/>
      <!-- Shine -->
      <path d="M140 20 C140 20, 30 140, 30 230 C30 305, 78 360, 140 360 C202 360, 250 305, 250 230 C250 140, 140 20, 140 20Z"
        fill="url(#shineGrad)"/>
      <!-- Inner highlight -->
      <ellipse cx="108" cy="155" rx="22" ry="38" fill="white" opacity="0.07" transform="rotate(-20 108 155)"/>
      <!-- Cross symbol -->
      <rect x="122" y="220" width="36" height="10" rx="5" fill="white" opacity="0.25"/>
      <rect x="136" y="206" width="10" height="36" rx="5" fill="white" opacity="0.25"/>
    </svg>
  </div>

  <!-- Stats bar -->
  <div class="hero-stats">
    <div class="hero-stat">
      <div class="hero-stat-number">56<span>days</span></div>
      <div class="hero-stat-label">Donation cooldown period</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-number"><span>&lt;</span>3s</div>
      <div class="hero-stat-label">Notification delivery</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-number">8</div>
      <div class="hero-stat-label">Blood types supported</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-number">0</div>
      <div class="hero-stat-label">App store required</div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section-how" id="how">
  <div class="section-label">The process</div>
  <h2 class="section-title reveal">From emergency to donor — in three steps.</h2>

  <div class="steps-grid reveal">
    <div class="step">
      <div class="step-number">01</div>
      <div class="step-icon">🏥</div>
      <h3 class="step-title">Hospital creates a request</h3>
      <p class="step-desc">A verified hospital posts an emergency blood request specifying blood type, urgency level, and units needed. The system activates instantly.</p>
    </div>
    <div class="step">
      <div class="step-number">02</div>
      <div class="step-icon">⚡</div>
      <h3 class="step-title">System matches donors</h3>
      <p class="step-desc">Wareed's matching engine scans all available donors by blood type compatibility, city, and donation cooldown — and notifies them simultaneously.</p>
    </div>
    <div class="step">
      <div class="step-number">03</div>
      <div class="step-icon">🩸</div>
      <h3 class="step-title">Donor responds live</h3>
      <p class="step-desc">Matched donors receive an instant push notification. One tap to accept. The hospital sees the response in real time — no refresh needed.</p>
    </div>
  </div>
</section>

<!-- BLOOD TYPES -->
<section class="section-types" id="blood-types">
  <div class="types-header reveal">
    <div>
      <div class="section-label" style="color:var(--blood)">Compatibility</div>
      <h2 class="section-title">All eight<br><em style="color:var(--blood)">blood types</em><br>supported.</h2>
    </div>
    <p class="types-desc">Wareed's matching engine uses the full ABO + Rh compatibility chart. Hover any type to learn about its donor role.</p>
  </div>

  <div class="blood-types-grid reveal">
    <div class="blood-type-card">
      <div class="bt-label">O−</div>
      <div class="bt-desc">Universal Donor</div>
      <div class="bt-universal">Donates to all</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">O+</div>
      <div class="bt-desc">Most common</div>
      <div class="bt-universal">Donates to O+, A+, B+, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">A−</div>
      <div class="bt-desc">Rare type</div>
      <div class="bt-universal">Donates to A−, A+, AB−, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">A+</div>
      <div class="bt-desc">Common type</div>
      <div class="bt-universal">Donates to A+, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">B−</div>
      <div class="bt-desc">Rare type</div>
      <div class="bt-universal">Donates to B−, B+, AB−, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">B+</div>
      <div class="bt-desc">Common type</div>
      <div class="bt-universal">Donates to B+, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">AB−</div>
      <div class="bt-desc">Rarest type</div>
      <div class="bt-universal">Donates to AB−, AB+</div>
    </div>
    <div class="blood-type-card">
      <div class="bt-label">AB+</div>
      <div class="bt-desc">Universal Receiver</div>
      <div class="bt-universal">Receives from all</div>
    </div>
  </div>
</section>

<!-- TRUST -->
<section class="section-trust" id="trust">
  <div class="trust-grid">
    <div class="trust-left reveal">
      <div class="section-label">Trust system</div>
      <h2 class="section-title">Donors earn<br><em>badges</em><br>over time.</h2>
      <p class="trust-tagline">Every confirmed donation builds your reputation on Wareed. Trusted donors are prioritized in the matching engine — giving them the chance to save more lives.</p>

      <div class="trust-badges">
        <div class="trust-badge">
          <div class="trust-badge-icon new">🌱</div>
          <div class="trust-badge-info">
            <div class="trust-badge-name">New Donor</div>
            <div class="trust-badge-req">Account created & verified</div>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge-icon active">💙</div>
          <div class="trust-badge-info">
            <div class="trust-badge-name">Active Donor</div>
            <div class="trust-badge-req">1+ confirmed donation</div>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge-icon trusted">✅</div>
          <div class="trust-badge-info">
            <div class="trust-badge-name">Trusted Donor</div>
            <div class="trust-badge-req">3+ confirmed donations · priority matching</div>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge-icon hero">⭐</div>
          <div class="trust-badge-info">
            <div class="trust-badge-name">Hero Donor</div>
            <div class="trust-badge-req">10+ confirmed donations · top priority</div>
          </div>
        </div>
      </div>
    </div>

    <div class="trust-right reveal">
      <div class="trust-visual">
        <div class="trust-visual-title">Platform overview</div>
        <div class="trust-stat-row">
          <span class="trust-stat-key">Notification speed</span>
          <span class="trust-stat-val">&lt;<span>3</span>s</span>
        </div>
        <div class="trust-stat-row">
          <span class="trust-stat-key">Hospital verification</span>
          <span class="trust-stat-val">Admin <span>approved</span></span>
        </div>
        <div class="trust-stat-row">
          <span class="trust-stat-key">Donor cooldown</span>
          <span class="trust-stat-val"><span>56</span> days</span>
        </div>
        <div class="trust-stat-row">
          <span class="trust-stat-key">App install required</span>
          <span class="trust-stat-val"><span>No</span> — PWA</span>
        </div>
        <div class="trust-stat-row">
          <span class="trust-stat-key">Real-time updates</span>
          <span class="trust-stat-val"><span>Live</span></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section-cta">
  <div class="cta-content reveal">
    <div class="cta-overline">Join Wareed today</div>
    <h2 class="cta-title">Your blood type<br><em>is someone's</em><br>lifeline.</h2>
    <p class="cta-sub">Register as a donor. It takes two minutes.<br>The difference it makes — is everything.</p>
    <div class="cta-buttons">
      <a href="{{ route('register') }}" class="btn-cta-white">
        Register as Donor
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
          <path d="M2 7h10M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <a href="{{ route('hospital.register') }}" class="btn-cta-outline">
        Register Hospital
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-logo">Wareed · وريد</div>
  <div class="footer-copy">© 2025 Wareed Blood Donation Network · Egypt</div>
  <ul class="footer-links">
    <li><a href="{{ route('login') }}">Donor login</a></li>
    <li><a href="{{ route('hospital.login') }}">Hospital login</a></li>
    <li><a href="{{ route('admin.login') }}">Admin</a></li>
  </ul>
</footer>

<script>
  /* Custom cursor */
  const cursor = document.getElementById('cursor');
  const ring   = document.getElementById('cursor-ring');
  let mx = 0, my = 0, rx = 0, ry = 0;

  document.addEventListener('mousemove', e => {
    mx = e.clientX; my = e.clientY;
    cursor.style.left = mx + 'px';
    cursor.style.top  = my + 'px';
  });

  (function animateRing() {
    rx += (mx - rx) * 0.12;
    ry += (my - ry) *.12;
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    requestAnimationFrame(animateRing);
  })();

  document.querySelectorAll('a, button').forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursor.style.width = '18px';
      cursor.style.height = '18px';
      ring.style.width = '56px';
      ring.style.height = '56px';
    });
    el.addEventListener('mouseleave', () => {
      cursor.style.width = '10px';
      cursor.style.height = '10px';
      ring.style.width = '36px';
      ring.style.height = '36px';
    });
  });

  /* Blood drip effect */
  const hero = document.querySelector('.hero-bg');
  for (let i = 0; i < 8; i++) {
    const drip = document.createElement('div');
    drip.className = 'hero-drip';
    drip.style.left = (Math.random() * 100) + '%';
    drip.style.height = (60 + Math.random() * 120) + 'px';
    drip.style.animationDuration = (4 + Math.random() * 8) + 's';
    drip.style.animationDelay = (Math.random() * 6) + 's';
    hero.appendChild(drip);
  }

  /* Scroll reveal */
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver(entries => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 80);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  reveals.forEach(el => observer.observe(el));
</script>

</body>
</html>