<?php get_header(); ?>

<div class="container" style="margin-top: 30px; padding-bottom: 100px;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="dyat-single-grid">
                <div class="gallery-side">
                    <div class="main-view">
                        <img src="<?php the_post_thumbnail_url('full'); ?>" id="dyat-expanded" alt="<?php the_title(); ?>">
                    </div>

                    <?php
                    $images = get_field('anh_kho_do');
                    if ($images): ?>
                        <div class="thumbs-list">
                            <div class="thumb-item">
                                <img src="<?php the_post_thumbnail_url('thumbnail'); ?>"
                                    onclick="document.getElementById('dyat-expanded').src='<?php the_post_thumbnail_url('full'); ?>'">
                            </div>
                            <?php foreach ($images as $img): ?>
                                <div class="thumb-item">
                                    <img src="<?php echo $img['sizes']['thumbnail']; ?>"
                                        onclick="document.getElementById('dyat-expanded').src='<?php echo $img['url']; ?>'">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-side">
                    <h1 class="dyat-title"><?php the_title(); ?></h1>
                    <div class="dyat-ms">Mã số: <span>#<?php the_ID(); ?></span></div>

                    <div class="dyat-stats-box">
                        <div class="stat-line">
                            <span class="label"><i class="fas fa-trophy"></i> Rank:</span>
                            <strong class="value"><?php the_field('rank_pubg'); ?></strong>
                        </div>
                        <div class="stat-line">
                            <span class="label"><i class="fas fa-check-circle"></i> Tình trạng:</span>
                            <strong class="value" style="color: #00ff00;">SẴN SÀNG</strong>
                        </div>
                    </div>

                    <div class="dyat-price-box">
                        <span class="price-label">Giá sở hữu ngay</span>
                        <div class="price-val"><?php echo number_format(get_field('gia_ban')); ?>đ</div>
                    </div>

                    <div class="dyat-purchase-btn-wrapper">
                        <?php if (get_field('is_sold') == 'yes'): ?>
                            <button class="btn-dyat-sold" disabled>ĐÃ BÁN</button>
                        <?php else: ?>
                            <button id="btn-buy-now" data-id="<?php the_ID(); ?>" class="btn-dyat-buy">
                                MUA NGAY <i class="fas fa-shopping-cart"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div id="account-result" class="dyat-result-box" style="display:none;">
                        <h4><i class="fas fa-gift"></i> GIAO DỊCH THÀNH CÔNG!</h4>
                        <div class="acc-info">
                            <p>Tài khoản: <strong id="res-user"></strong></p>
                            <p>Mật khẩu: <strong id="res-pass"></strong></p>
                        </div>
                        <small>Thông tin này đã được lưu vào Lịch sử mua hàng của bạn.</small>
                    </div>
                </div>
            </div>
    <?php endwhile;
    endif; ?>
</div>

<?php get_footer(); ?>