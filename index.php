<?php require_once __DIR__ . '/config/app.php'; ensure_rating_column($pdo); ensure_messages_table($pdo); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herbite Gummy - Produk Berkualitas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="includes/app.css">
    <link rel="stylesheet" href="includes/toko.css">
    <link rel="stylesheet" href="includes/ulasan.css?v=<?= filemtime(__DIR__ . '/includes/ulasan.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <img src="img/logo.png" alt="Herbite Gummy" class="logo-img" onerror="this.style.display='none'">
                <span>Herbite Gummy</span>
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#produk" class="nav-link">Produk</a></li>
                <li><a href="#etalase" class="nav-link">Belanja</a></li>
                <li><a href="#tentang" class="nav-link">Tentang</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
            </ul>
            <div class="nav-icons"><a href="<?= user() ? (user()['role']==='admin' ? 'admin/dashboard.php' : 'customer/pesanan.php') : 'login.php' ?>" class="btn-primary" style="padding:9px 14px;font-size:.9rem"><?= user() ? 'Akun Saya' : 'Login / Daftar' ?></a>
                <div class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== 1. HOME ===== -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Produk <span class="highlight">Terbaik</span> Untuk Anda</h1>
            <p class="hero-subtitle">"Herbite Gummy adalah inovasi suplemen herbal masa kini dalam wujud gummy yang lezat. Dapatkan kebaikan nutrisi alam secara praktis dalam setiap gigitan."</p>
            <!-- ID btnBelanja untuk diarahkan ke kontak via JS -->
            <a href="#etalase" class="btn-primary" id="btnBelanja">Belanja Sekarang <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="hero-image">
            <img src="produkbaru.jpg" alt="Herbite Gummy" onerror="this.src='https://via.placeholder.com/500x500?text=Hero+Image'">
        </div>
    </section>

    <!-- ===== 2. PRODUK (Satu Layar Penuh) ===== -->
    <section class="produk" id="produk">
        
        <div class="produk-header">
            <h2 class="produk-title">PRODUK <span>KAMI</span></h2>
            <p class="produk-subtitle">Mari berkenalan dengan Herbite Gummy</p>
        </div>

        <div class="produk-container">
            
            <div class="produk-center">
                <img src="soloo.png" alt="Kemasan Herbite Gummy" class="produk-img" id="mainProductImage" onerror="this.src='https://via.placeholder.com/400x600?text=soloo.png'">
            </div>

            <!-- Garis Penunjuk (SVG) -->
            <svg class="lines-svg" viewBox="0 0 1000 600" preserveAspectRatio="none">
                <polyline class="line-path kiri" points="190,120 310,120 340,160" />
                <polyline class="line-path kiri" points="160,260 300,260 340,260" />
                <polyline class="line-path kiri" points="170,400 310,400 340,370" />
                <polyline class="line-path kiri" points="190,520 315,520 340,450" />
                
                <polyline class="line-path kanan" points="810,150 690,150 660,180" />
                <polyline class="line-path kanan" points="840,300 700,300 660,290" />
                <polyline class="line-path kanan" points="800,450 685,450 660,390" />
            </svg>

            <!-- Label Informasi Kiri -->
            <div class="info-label kiri label-1"><span class="dot"></span><div class="label-box"><i class="fas fa-leaf"></i> 1. Kunyit</div></div>
            <div class="info-label kiri label-2"><span class="dot"></span><div class="label-box"><i class="fas fa-seedling"></i> 2. Jahe</div></div>
            <div class="info-label kiri label-3"><span class="dot"></span><div class="label-box"><i class="fas fa-spa"></i> 3. Sereh</div></div>
            <div class="info-label kiri label-4"><span class="dot"></span><div class="label-box"><i class="fas fa-cube"></i> 4. Gelatin</div></div>

            <!-- Label Informasi Kanan -->
            <div class="info-label kanan label-5"><span class="dot"></span><div class="label-box"><i class="fas fa-apple-alt"></i> 5. Perisa Buah</div></div>
            <div class="info-label kanan label-6"><span class="dot"></span><div class="label-box"><i class="fas fa-candy-cane"></i> 6. Gula Alami</div></div>
            <div class="info-label kanan label-7"><span class="dot"></span><div class="label-box"><i class="fas fa-flask"></i> 7. Asam Sitrat</div></div>

        </div>
    </section>

    <!-- ===== 2b. TOKO / ETALASE (desain toko online) ===== -->
    <?php
    $products = $pdo->query("SELECT * FROM products WHERE active=1 ORDER BY id")->fetchAll();
    $soldMap = [];
    foreach ($pdo->query("SELECT oi.product_id, SUM(oi.quantity) AS qty FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.status NOT IN ('cancelled','waiting_payment') GROUP BY oi.product_id") as $row) { $soldMap[$row['product_id']] = (int)$row['qty']; }
    // Rating & ulasan per produk (dari testimoni yang ditampilkan, lewat order_items)
    $rateMap = []; $revMap = [];
    foreach ($pdo->query("SELECT oi.product_id, t.id, t.rating, t.message, u.name FROM testimonials t JOIN users u ON u.id=t.user_id JOIN order_items oi ON oi.order_id=t.order_id WHERE t.is_published=1 AND t.rating IS NOT NULL GROUP BY oi.product_id, t.id, t.rating, t.message, u.name ORDER BY t.id DESC") as $row) {
        $pid = (int)$row['product_id'];
        $rateMap[$pid]['sum'] = ($rateMap[$pid]['sum'] ?? 0) + (int)$row['rating'];
        $rateMap[$pid]['n']   = ($rateMap[$pid]['n'] ?? 0) + 1;
        if (count($revMap[$pid] ?? []) < 3) $revMap[$pid][] = $row;
    }
    $isCustomer = user() && user()['role'] === 'customer';
    $waNumber = setting($pdo, 'whatsapp', '6285378582198');
    ?>
    <section class="toko" id="etalase">
        <div class="toko-inner">
            <div class="toko-head">
                <div>
                    <span class="toko-eyebrow"><i class="fas fa-store"></i> Toko Resmi Herbite Gummy</span>
                    <h2>Belanja <span>Gummy Herbal</span></h2>
                    <p>Pilih rasa favoritmu, atur jumlah, lalu checkout dalam hitungan detik.</p>
                </div>
                <label class="toko-search"><i class="fas fa-search"></i><input type="search" id="tokoSearch" placeholder="Cari rasa... (mis. mangga)" autocomplete="off"></label>
            </div>

            <div class="toko-perks">
                <div><i class="fas fa-leaf"></i><b>Bahan Herbal</b><small>Kunyit, jahe &amp; sereh</small></div>
                <div><i class="fas fa-calendar-check"></i><b>Stok Harian</b><small>Dibuat fresh tiap hari</small></div>
                <div><i class="fas fa-qrcode"></i><b>Bayar QRIS</b><small>Upload bukti, cepat diverifikasi</small></div>
                <div><i class="fas fa-motorcycle"></i><b>Antar / Ambil</b><small>Gratis ongkir beli 5+ · antar s.d. 15.00</small></div>
            </div>

            <div class="toko-filter" id="tokoFilter">
                <button type="button" class="chip active" data-f="all">Semua</button>
                <button type="button" class="chip" data-f="ready">Ready</button>
                <button type="button" class="chip" data-f="soon">Coming Soon</button>
                <span class="toko-count"><b id="tokoCount"><?= count($products) ?></b> produk</span>
            </div>

            <div class="toko-grid" id="tokoGrid">
            <?php foreach ($products as $pr):
                $stock = today_stock($pdo, $pr['id']);
                $left  = max(0, (int)$stock['stock_total'] - (int)$stock['stock_sold']);
                $soon  = $pr['status'] === 'coming_soon';
                $ready = !$soon && $left > 0;
                $total = max(1, (int)$stock['stock_total']);
                $pct   = $ready ? max(6, min(100, round($left / $total * 100))) : 0;
                $state = $soon ? 'soon' : ($ready ? 'ready' : 'out');
                $sold  = $soldMap[$pr['id']] ?? 0;
                $rn    = $rateMap[$pr['id']]['n'] ?? 0;
                $ravg  = $rn ? $rateMap[$pr['id']]['sum'] / $rn : 0;
            ?>
                <article class="p-card <?= $state ?>" data-id="<?= (int)$pr['id'] ?>" data-state="<?= $soon ? 'soon' : 'ready' ?>" data-name="<?= e(strtolower($pr['name'].' '.$pr['flavor'])) ?>">
                    <div class="p-media">
                        <span class="p-badge <?= $state ?>"><?= $soon ? 'Coming Soon' : ($ready ? 'Ready' : 'Stok Habis') ?></span>
                        <img src="<?= e($pr['image'] ?: 'produkbaru.jpg') ?>" alt="<?= e($pr['name']) ?>" loading="lazy">
                    </div>
                    <div class="p-body">
                        <span class="p-flavor">Rasa <?= e($pr['flavor']) ?></span>
                        <h3><?= e($pr['name']) ?></h3>
                        <div class="p-rate"><?php if ($rn): ?><?= stars_html($ravg) ?> <b><?= number_format($ravg,1) ?></b> (<?= $rn ?> ulasan)<?php else: ?><?= stars_html(0) ?> Belum ada ulasan<?php endif; ?></div>
                        <div class="p-price"><?= rupiah($pr['price']) ?><small>/ pack</small></div>
                        <button type="button" class="p-detail"><i class="fas fa-circle-info"></i> Lihat detail produk</button>
                        <div class="p-meta">
                            <?php if ($sold > 0): ?><span><i class="fas fa-fire"></i> Terjual <?= $sold ?></span><?php endif; ?>
                            <span><i class="fas fa-shield-heart"></i> Halal &amp; higienis</span>
                        </div>
                        <?php if ($ready): ?>
                            <div class="p-stock"><div class="bar"><i style="width:<?= $pct ?>%"></i></div><small>Sisa stok hari ini: <b><?= $left ?></b></small></div>
                        <?php elseif ($soon): ?>
                            <div class="p-stock"><small>Segera hadir, nantikan ya!</small></div>
                        <?php else: ?>
                            <div class="p-stock"><small>Stok hari ini sudah habis, cek lagi besok.</small></div>
                        <?php endif; ?>

                        <?php if ($ready && $isCustomer): ?>
                            <form class="p-buy" action="customer/checkout.php" method="get">
                                <input type="hidden" name="id" value="<?= (int)$pr['id'] ?>">
                                <div class="qty">
                                    <button type="button" data-d="-1" aria-label="Kurangi">&minus;</button>
                                    <input type="number" name="qty" value="1" min="1" max="<?= $left ?>" aria-label="Jumlah">
                                    <button type="button" data-d="1" aria-label="Tambah">+</button>
                                </div>
                                <button class="p-btn" type="submit"><i class="fas fa-bag-shopping"></i> Beli Sekarang</button>
                            </form>
                        <?php elseif ($ready && !user()): ?>
                            <a class="p-btn full" href="login.php"><i class="fas fa-right-to-bracket"></i> Login untuk Pesan</a>
                        <?php elseif ($ready): ?>
                            <a class="p-btn full ghost" href="admin/dashboard.php">Kelola di Dashboard</a>
                        <?php elseif ($soon): ?>
                            <a class="p-btn full ghost" target="_blank" rel="noopener" href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Halo Herbite Gummy, saya tertarik dengan rasa '.$pr['flavor'].'. Kapan tersedia?') ?>"><i class="fab fa-whatsapp"></i> Tanya Ketersediaan</a>
                        <?php else: ?>
                            <button class="p-btn full" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
            </div>

            <!-- Detail tiap produk (ditampilkan di modal) -->
            <?php foreach ($products as $pr):
                $stock = today_stock($pdo, $pr['id']);
                $left  = max(0, (int)$stock['stock_total'] - (int)$stock['stock_sold']);
                $soon  = $pr['status'] === 'coming_soon';
                $ready = !$soon && $left > 0;
                $sold  = $soldMap[$pr['id']] ?? 0;
                $rn    = $rateMap[$pr['id']]['n'] ?? 0;
                $ravg  = $rn ? $rateMap[$pr['id']]['sum'] / $rn : 0;
            ?>
            <template id="pd-<?= (int)$pr['id'] ?>">
                <div class="pd-grid">
                    <div class="pd-media"><span class="p-badge <?= $soon ? 'soon' : ($ready ? 'ready' : 'out') ?>"><?= $soon ? 'Coming Soon' : ($ready ? 'Ready' : 'Stok Habis') ?></span><img src="<?= e($pr['image'] ?: 'produkbaru.jpg') ?>" alt="<?= e($pr['name']) ?>"></div>
                    <div class="pd-info">
                        <span class="p-flavor">Rasa <?= e($pr['flavor']) ?></span>
                        <h3><?= e($pr['name']) ?></h3>
                        <div class="p-rate"><?php if ($rn): ?><?= stars_html($ravg) ?> <b><?= number_format($ravg,1) ?></b> dari 5 &middot; <?= $rn ?> ulasan<?php else: ?><?= stars_html(0) ?> Belum ada ulasan<?php endif; ?></div>
                        <div class="pd-price"><?= rupiah($pr['price']) ?><small>/ pack</small></div>
                        <div class="pd-stats">
                            <div><b>#<?= (int)$pr['id'] ?></b><small>ID Produk</small></div>
                            <div><b><?= $soon ? '-' : $left ?></b><small>Sisa stok hari ini</small></div>
                            <div><b><?= $sold ?></b><small>Terjual</small></div>
                        </div>
                        <h4>Ulasan Pembeli</h4>
                        <div class="pd-rev">
                        <?php if (!empty($revMap[$pr['id']])): foreach ($revMap[$pr['id']] as $rv): ?>
                            <blockquote><?= stars_html($rv['rating']) ?><br>&ldquo;<?= e($rv['message']) ?>&rdquo;<cite>&mdash; <?= e($rv['name']) ?></cite></blockquote>
                        <?php endforeach; else: ?>
                            <span class="app-muted">Belum ada ulasan untuk produk ini.</span>
                        <?php endif; ?>
                        </div>
                        <div class="pd-cta">
                        <?php if ($ready && $isCustomer): ?>
                            <form class="p-buy" action="customer/checkout.php" method="get">
                                <input type="hidden" name="id" value="<?= (int)$pr['id'] ?>">
                                <div class="qty"><button type="button" data-d="-1" aria-label="Kurangi">&minus;</button><input type="number" name="qty" value="1" min="1" max="<?= $left ?>" aria-label="Jumlah"><button type="button" data-d="1" aria-label="Tambah">+</button></div>
                                <button class="p-btn" type="submit"><i class="fas fa-bag-shopping"></i> Beli Sekarang</button>
                            </form>
                        <?php elseif ($ready && !user()): ?>
                            <a class="p-btn full" href="login.php"><i class="fas fa-right-to-bracket"></i> Login untuk Pesan</a>
                        <?php elseif ($ready): ?>
                            <a class="p-btn full ghost" href="admin/dashboard.php">Kelola di Dashboard</a>
                        <?php elseif ($soon): ?>
                            <a class="p-btn full ghost" target="_blank" rel="noopener" href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Halo Herbite Gummy, saya tertarik dengan rasa '.$pr['flavor'].'. Kapan tersedia?') ?>"><i class="fab fa-whatsapp"></i> Tanya Ketersediaan</a>
                        <?php else: ?>
                            <button class="p-btn full" disabled>Stok Habis</button>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </template>
            <?php endforeach; ?>

            <p class="toko-empty" id="tokoEmpty" hidden><i class="fas fa-magnifying-glass"></i> Produk tidak ditemukan.</p>

            <?php if (!user()): ?>
            <div class="toko-cta"><div><b>Belum punya akun?</b><span>Daftar gratis untuk mulai memesan dan memantau status pesananmu.</span></div><a class="p-btn" href="register.php">Daftar Sekarang</a></div>
            <?php endif; ?>

            <ol class="toko-steps">
                <li><b>1</b><span>Pilih rasa &amp; jumlah</span></li>
                <li><b>2</b><span>Isi alamat &amp; lokasi</span></li>
                <li><b>3</b><span>Bayar via QRIS</span></li>
                <li><b>4</b><span>Pesanan diproses</span></li>
            </ol>
        </div>
    </section>

    <!-- ===== 3. TENTANG (Satu Layar Penuh, Font Kecil) ===== -->
    <section class="tentang" id="tentang">
        <div class="tentang-content">
            <h2 class="fade-up">Tentang <span class="highlight-tentang">Kami</span></h2>
            <p class="tentang-pembuka fade-up delay-1">
                Selamat datang di Herbite Gummy, pelopor camilan sehat yang memadukan kebaikan rempah alami nusantara dengan kelezatan permen jeli (gummy) modern. Kami menghadirkan solusi praktis dan menyenangkan untuk merawat kesehatan tanpa rasa membosankan.
            </p>
            
            <div class="visi-misi-grid">
                <!-- Visi -->
                <div class="visi-box fade-up delay-2">
                    <h3><i class="fas fa-eye"></i> Visi Kami</h3>
                    <p>Menjadi pelopor camilan sehat berbasis herbal yang dipercaya untuk mendukung gaya hidup aktif dan seimbang.</p>
                </div>
                
                <!-- Misi -->
                <div class="misi-box fade-up delay-3">
                    <h3><i class="fas fa-bullseye"></i> Misi Kami</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> <span><strong>Inovasi Alami:</strong> Mengolah bahan herbal pilihan menjadi produk modern yang mudah dikonsumsi.</span></li>
                        <li><i class="fas fa-check-circle"></i> <span><strong>Kualitas Tanpa Kompromi:</strong> Mengutamakan keamanan pangan, higienitas, serta standar halal.</span></li>
                        <li><i class="fas fa-check-circle"></i> <span><strong>Kesehatan Menyenangkan:</strong> Menghadirkan produk kesehatan yang lezat dan menyenangkan.</span></li>
                    </ul>
                </div>
            </div>
            
            <p class="tentang-penutup fade-up delay-4">Nikmati kebaikan alam dalam setiap gigitannya bersama Herbite Gummy!</p>
        </div>
    </section>

    <!-- ===== 4. KONTAK (Kirim ke Admin) ===== -->
    <section class="kontak" id="kontak">
        <h2>Hubungi <span class="highlight">Kami</span></h2>
        <form class="kontak-form" id="kontakForm">
            <input type="hidden" id="kCsrf" value="<?= e(csrf_token()) ?>">
            <input type="text" id="nama" placeholder="Nama Anda" maxlength="120" value="<?= e(user()['name'] ?? '') ?>" required>
            <input type="text" id="kontakInfo" placeholder="Email / No. HP (opsional, agar admin bisa membalas)" maxlength="190" value="<?= e(user()['email'] ?? '') ?>">
            <textarea id="pesan" placeholder="Pertanyaan atau Permintaan Anda" rows="5" maxlength="2000" required></textarea>
            <button type="submit" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Pesan
            </button>
        </form>
    </section>

    <?php
    $testis = $pdo->query("SELECT t.message,t.rating,t.created_at,u.name FROM testimonials t JOIN users u ON u.id=t.user_id WHERE t.is_published=1 ORDER BY t.id DESC LIMIT 12")->fetchAll();
    $tSum = $pdo->query("SELECT COUNT(rating) n, AVG(rating) a FROM testimonials WHERE is_published=1 AND rating IS NOT NULL")->fetch();
    ?>
    <section class="testi" id="testimoni">
        <div class="testi-head">
            <h2>Testimoni <span>Pelanggan</span></h2>
            <?php if ((int)$tSum['n'] > 0): ?><div class="testi-sum"><span class="big"><?= number_format((float)$tSum['a'],1) ?></span><?= stars_html((float)$tSum['a']) ?><span><?= (int)$tSum['n'] ?> penilaian</span></div><?php endif; ?>
        </div>
        <?php if (!$testis): ?>
            <p class="testi-empty">Belum ada testimoni yang ditampilkan.</p>
        <?php else: ?>
        <div class="tc" id="tc">
            <button type="button" class="tc-nav tc-prev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
            <div class="tc-track">
            <?php foreach ($testis as $t): ?>
                <article class="tc-card">
                    <?php if ($t['rating']): ?><?= stars_html($t['rating']) ?><?php endif; ?>
                    <p class="tc-msg">&ldquo;<?= e($t['message']) ?>&rdquo;</p>
                    <div class="tc-by"><span class="tc-av"><?= e(strtoupper(mb_substr($t['name'],0,1))) ?></span><div><b><?= e($t['name']) ?></b><small><?= e(date('d M Y', strtotime($t['created_at']))) ?></small></div></div>
                </article>
            <?php endforeach; ?>
            </div>
            <button type="button" class="tc-nav tc-next" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
            <div class="tc-dots" id="tcDots"></div>
        </div>
        <?php endif; ?>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Herbitegummy. All rights reserved.</p>
    </footer>

    <div class="pd-modal" id="pdModal" role="dialog" aria-modal="true" aria-label="Detail produk"><div class="pd-box"><button type="button" class="pd-close" aria-label="Tutup"><i class="fas fa-xmark"></i></button><div class="pd-body"></div></div></div>

    <!-- NOTIFIKASI -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notifText">Berhasil!</span>
    </div>

    <script src="script.js?v=<?= filemtime(__DIR__ . '/script.js') ?>"></script>
    <script src="includes/toko.js"></script>
    <script src="includes/ulasan.js"></script>
</body>
</html>