<?php get_header(); ?>
<div class="container" style="padding-bottom: 80px; padding-top: 30px;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="shop-breadcrumb">
                <a href="<?php echo home_url(); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <i class="fa-solid fa-chevron-right separator"></i>
                <a href="<?php echo home_url('/nick-pubg/'); ?>">Nick PUBG</a>
                <i class="fa-solid fa-chevron-right separator"></i>
                <span><?php the_title(); ?></span>
            </div>
            <div class="dyat-single-grid">
                <div class="gallery-side">
                    <div class="gallery-container">
                        <div class="swiper swiper-detail">
                            <div class="swiper-wrapper">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="swiper-slide"><img src="<?php the_post_thumbnail_url('full'); ?>" alt="Main"></div>
                                <?php endif; ?>
                                <?php
                                $images = get_field('anh_kho_do');
                                if ($images): foreach ($images as $img): ?>
                                        <div class="swiper-slide"><img src="<?php echo esc_url(is_array($img) ? $img['url'] : $img); ?>"
                                                alt="Gallery"></div>
                                <?php endforeach;
                                endif; ?>
                            </div>
                            <div class="swiper-button-next" style="color:#fff"></div>
                            <div class="swiper-button-prev" style="color:#fff"></div>
                        </div>

                        <div class="swiper swiper-thumbs">
                            <div class="swiper-wrapper">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="swiper-slide"><img src="<?php the_post_thumbnail_url('thumbnail'); ?>"></div>
                                <?php endif; ?>
                                <?php if ($images): foreach ($images as $img): ?>
                                        <div class="swiper-slide"><img
                                                src="<?php echo esc_url(is_array($img) ? $img['sizes']['thumbnail'] : $img); ?>"></div>
                                <?php endforeach;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-side">
                    <div class="account-ms-single">Mã số: <span>#<?php the_ID(); ?></span></div>
                    <h1 class="single-nick-title"><?php the_title(); ?></h1>

                    <div class="detail-specs-grid">
                        <div class="spec-node">
                            <span>RANK</span>
                            <strong><i class="fa-solid fa-trophy"></i> <?php the_field('rank_pubg'); ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>LEVEL</span>
                            <strong><i class="fa-solid fa-star"></i> <?php the_field('level_acc'); ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>SKIN SÚNG</span>
                            <strong><i class="fa-solid fa-gun"></i> <?php the_field('skin_sung_count'); ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>SKIN ÁO</span>
                            <strong><i class="fa-solid fa-shirt"></i> <?php the_field('skin_ao_count'); ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>SKIN XE</span>
                            <strong><i class="fa-solid fa-car"></i> <?php echo get_field('skin_xe_count') ?: '0'; ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>THÀNH TỰU</span>
                            <strong><i class="fa-solid fa-medal"></i>
                                <?php echo get_field('achievement_pts') ?: '0'; ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>ROYALE PASS</span>
                            <strong><i class="fa-solid fa-crown"></i> <?php echo get_field('rp_season') ?: 'Không'; ?></strong>
                        </div>
                        <div class="spec-node">
                            <span>SERVER</span>
                            <strong><i class="fa-solid fa-globe"></i>
                                <?php echo get_field('server_pubg') ?: 'Quốc tế'; ?></strong>
                        </div>
                    </div>

                    <?php if (get_field('sung_nang_cap')): ?>
                        <div class="special-guns-node">
                            <i class="fa-solid fa-fire-flame-curved"></i> Súng nâng cấp:
                            <strong><?php the_field('sung_nang_cap'); ?></strong>
                        </div>
                    <?php endif; ?>

                    <div class="single-price-area">
                        <?php
                        $price_origin = (int)get_field('gia_ban');
                        $price_sale = (int)get_field('gia_sale');
                        if ($price_sale > 0) : ?>
                            <span class="old-price-single"><?php echo number_format($price_origin); ?>đ</span>
                            <div class="price-ribbon-single"><?php echo number_format($price_sale); ?>đ</div>
                        <?php else : ?>
                            <div class="price-ribbon-single"><?php echo number_format($price_origin); ?>đ</div>
                        <?php endif; ?>

                        <div class="purchase-action-wrapper">
                            <?php if (is_user_logged_in()) : ?>
                                <button class="btn-buy-now-single" id="btn-buy-now" data-id="<?php the_ID(); ?>">
                                    XÁC NHẬN MUA NGAY <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            <?php else : ?>
                                <a href="<?php echo home_url('/auth/'); ?>" class="btn-buy-now-single btn-login-to-buy"
                                    style="text-decoration: none; display: block; background: #333 !important; color: #fff !important; text-align: center;">
                                    ĐĂNG NHẬP ĐỂ MUA <i class="fa-solid fa-right-to-bracket"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="guarantee-note">
                        <i class="fa-solid fa-shield-check"></i> Bảo hành lỗi Back Nick vĩnh viễn, hoàn tiền 100%.
                    </div>
                </div>
            </div>
    <?php endwhile;
    endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var thumbs = new Swiper(".swiper-thumbs", {
            spaceBetween: 10,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesProgress: true
        });
        new Swiper(".swiper-detail", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            thumbs: {
                swiper: thumbs
            }
        });
    });
</script>

<div id="purchase-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fa-solid fa-circle-check"></i>
            <h2>MUA NICK THÀNH CÔNG</h2>
        </div>
        <div class="modal-body">
            <p>Thông tin tài khoản của bạn:</p>
            <div class="acc-info-box">
                <div class="info-field">
                    <span>Tài khoản:</span>
                    <strong id="res-user">---</strong>
                </div>
                <div class="info-field">
                    <span>Mật khẩu:</span>
                    <strong id="res-pass">---</strong>
                </div>
            </div>
            <div class="modal-note">
                <i class="fa-solid fa-circle-exclamation"></i> Vui lòng kiểm tra và đổi mật khẩu ngay để đảm bảo an
                toàn.
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-close-modal">ĐÓNG</button>
            <a href="<?php echo home_url('/lich-su-mua-hang/'); ?>" class="btn-history">LỊCH SỬ MUA</a>
        </div>
    </div>
</div>

<?php get_footer(); ?>