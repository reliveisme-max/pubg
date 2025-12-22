<?php get_header(); ?>

<div class="container">
    <section class="shoprito-slider" style="margin: 20px 0;">
        <div class="swiper mainSwiper">
            <div class="swiper-wrapper">
                <?php
                // Lấy dữ liệu từ ACF Repeater 'home_slider' trong Options Page
                if (have_rows('home_slider', 'option')):
                    while (have_rows('home_slider', 'option')): the_row();
                        $img = get_sub_field('bg_image'); // Chỉ lấy ảnh banner
                        $link = get_sub_field('button_link'); // Link gắn vào ảnh
                ?>
                        <div class="swiper-slide banner-item">
                            <?php if ($link): ?>
                                <a href="<?php echo esc_url($link); ?>">
                                <?php endif; ?>

                                <img src="<?php echo esc_url($img['url']); ?>" alt="Banner"
                                    style="width:100%; border-radius:12px; display:block;">

                                <?php if ($link): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php
                    endwhile;
                else:
                    ?>
                    <div class="swiper-slide banner-item">
                        <img src="https://via.placeholder.com/1200x450?text=Chưa+có+banner" alt="Default Banner"
                            style="width:100%; border-radius:12px;">
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <div class="section-title-wrapper" style="margin: 40px 0 20px;">
        <h2
            style="color: var(--color-accent); text-transform: uppercase; font-family: 'Rajdhani', sans-serif; font-size: 28px;">
            <i class="fas fa-fire"></i> Nick PUBG Mobile Mới Nhất
        </h2>
    </div>

    <div class="dyat-grid">
        <?php
        $args = array(
            'post_type' => 'nick-pubg',
            'posts_per_page' => 12,
            'meta_query' => array(
                array('key' => 'is_sold', 'value' => 'no', 'compare' => '=')
            )
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
                <div class="dyat-card">
                    <div class="card-media">
                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large');
                        endif; ?>
                        <div class="ms-tag">MS: #<?php the_ID(); ?></div>
                    </div>
                    <div class="card-info">
                        <h3 class="card-name"><?php the_title(); ?></h3>
                        <div class="bid-label">Rank: <strong><?php the_field('rank_pubg'); ?></strong></div>
                        <div class="card-action-row">
                            <div class="price-pill" style="color: var(--color-accent); font-weight: 800; font-size: 18px;">
                                <?php echo number_format(get_field('gia_ban')); ?>đ
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn-bid">XEM CHI TIẾT</a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else: ?>
            <p style="color: #888; text-align: center; width: 100%; padding: 50px 0;">Hiện chưa có sản phẩm nào đang bán.
            </p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".mainSwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>

<?php get_footer(); ?>