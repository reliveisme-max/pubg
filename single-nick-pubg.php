<?php get_header(); ?>

<div class="container single-nick-layout">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="single-grid">
                <div class="nick-gallery">
                    <div class="main-img-box">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" id="expandedImg" class="img-fluid">
                    </div>

                    <?php
                    $images = get_field('anh_kho_do');
                    if ($images): ?>
                        <div class="gallery-nav">
                            <?php foreach ($images as $image): ?>
                                <div class="nav-item">
                                    <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>"
                                        onclick="document.getElementById('expandedImg').src='<?php echo esc_url($image['url']); ?>'">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="nick-details-sidebar">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-ms">Mã số: #<?php the_ID(); ?></div>

                    <div class="info-card">
                        <div class="info-row"><span>Rank:</span> <strong><?php the_field('rank_pubg'); ?></strong></div>
                        <div class="info-row"><span>Tình trạng:</span> <strong class="text-success">Còn hàng</strong></div>
                    </div>

                    <div class="price-display">
                        <span class="label">Giá bán:</span>
                        <div class="value"><?php echo number_format(get_field('gia_ban')); ?>đ</div>
                    </div>

                    <div class="action-box">
                        <button id="btn-buy-now" data-id="<?php the_ID(); ?>" class="btn-buy">MUA NGAY</button>
                        <p class="note">* Nhận tài khoản & mật khẩu ngay sau khi thanh toán.</p>
                    </div>

                    <div id="account-result" class="result-box" style="display:none;">
                        <h4>MUA THÀNH CÔNG!</h4>
                        <p>Tài khoản: <strong id="res-user"></strong></p>
                        <p>Mật khẩu: <strong id="res-pass"></strong></p>
                    </div>
                </div>
            </div>

            <div class="nick-description">
                <h3>MÔ TẢ CHI TIẾT</h3>
                <div class="desc-content"><?php the_content(); ?></div>
            </div>

    <?php endwhile;
    endif; ?>
</div>

<?php get_footer(); ?>