<?php get_header(); ?>
<div class="container" style="padding-bottom: 80px;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="dyat-single-grid">

                <div class="gallery-side">
                    <div class="gallery-container">
                        <div class="swiper swiper-detail">
                            <div class="swiper-wrapper">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="swiper-slide"><img src="<?php the_post_thumbnail_url('full'); ?>"></div>
                                <?php endif; ?>
                                <?php
                                $images = get_field('anh_kho_do');
                                if ($images): foreach ($images as $img): ?>
                                        <div class="swiper-slide"><img src="<?php echo esc_url($img['url']); ?>"></div>
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
                                        <div class="swiper-slide"><img src="<?php echo esc_url($img['sizes']['thumbnail']); ?>"></div>
                                <?php endforeach;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-side">
                    <div style="color: #888; font-size: 14px; margin-bottom: 5px;">Mã số: <span
                            style="color:var(--color-accent); font-weight:700;">#<?php the_ID(); ?></span></div>
                    <h1><?php the_title(); ?></h1>

                    <div style="margin: 20px 0; border-top: 1px solid #333; padding-top: 20px;">
                        <p style="margin-bottom:8px;"><i class="fa-solid fa-trophy" style="width:25px; color:#888;"></i> Rank:
                            <strong><?php the_field('rank_pubg'); ?></strong>
                        </p>
                        <p><i class="fa-solid fa-circle-check" style="width:25px; color:#00ff00;"></i> Tình trạng: <strong>SẴN
                                SÀNG</strong></p>
                    </div>

                    <div class="price-val"><?php echo number_format(get_field('gia_ban'), 0, '.', ','); ?>đ</div>

                    <button class="btn-buy-now" id="btn-buy-now" data-id="<?php the_ID(); ?>">
                        MUA NGAY <i class="fa-solid fa-cart-shopping" style="margin-left:8px;"></i>
                    </button>

                    <div
                        style="margin-top: 20px; padding: 15px; background: rgba(255,174,0,0.1); border-radius: 8px; border: 1px dashed var(--color-accent); font-size: 13px; color: #ccc;">
                        <i class="fa-solid fa-shield-check"></i> Cam kết nick đúng như hình, bảo hành trọn đời lỗi back.
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
<?php get_footer(); ?>