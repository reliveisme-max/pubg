<?php get_header(); ?>

<div class="container dyat-single-layout">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="dyat-single-grid">
        <div class="gallery-side">
            <div class="main-view">
                <img src="<?php the_post_thumbnail_url('full'); ?>" id="dyat-expanded">
            </div>
            <?php 
                $images = get_field('anh_kho_do'); 
                if($images): ?>
            <div class="thumbs-list">
                <?php foreach($images as $img): ?>
                <img src="<?php echo $img['sizes']['thumbnail']; ?>"
                    onclick="document.getElementById('dyat-expanded').src='<?php echo $img['url']; ?>'">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="info-side">
            <h1 class="dyat-title"><?php the_title(); ?></h1>
            <div class="dyat-ms">Mã số: #<?php the_ID(); ?></div>

            <div class="dyat-stats-box">
                <div class="stat-line"><span>Rank:</span> <strong><?php the_field('rank_pubg'); ?></strong></div>
                <div class="stat-line"><span>Tình trạng:</span> <strong class="neon-text">SẴN SÀNG</strong></div>
            </div>

            <div class="dyat-price-box">
                <span class="label">Giá sở hữu ngay</span>
                <div class="val"><?php echo number_format(get_field('gia_ban')); ?>đ</div>
            </div>

            <div class="dyat-purchase-btn-wrapper">
                <?php if(get_field('is_sold') == 'yes'): ?>
                <button class="btn-dyat-sold" disabled>ĐÃ BÁN</button>
                <?php else: ?>
                <button id="btn-buy-now" data-id="<?php the_ID(); ?>" class="btn-dyat-buy">MUA NGAY <i
                        class="fas fa-shopping-cart"></i></button>
                <?php endif; ?>
            </div>

            <div id="account-result" class="dyat-result-box" style="display:none;">
                <h4>GIAO DỊCH HOÀN TẤT!</h4>
                <p>Tài khoản: <strong id="res-user"></strong></p>
                <p>Mật khẩu: <strong id="res-pass"></strong></p>
            </div>
        </div>
    </div>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>