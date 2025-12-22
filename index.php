<?php get_header(); ?>
<div class="container">
    <section class="shoprito-slider">
        <div class="swiper mainSwiper">
            <div class="swiper-wrapper">
                <?php if (have_rows('home_slider', 'option')): while (have_rows('home_slider', 'option')): the_row();
                        $img = get_sub_field('bg_image'); $link = get_sub_field('button_link'); ?>
                <div class="swiper-slide banner-item">
                    <?php if ($link): ?><a href="<?php echo esc_url($link); ?>"><?php endif; ?>
                        <img src="<?php echo esc_url($img['url']); ?>" alt="Banner"
                            style="width:100%; border-radius:12px; display:block;">
                        <?php if ($link): ?></a><?php endif; ?>
                </div>
                <?php endwhile; endif; ?>
            </div>
            <div class="swiper-button-next" style="color:#fff !important;"></div>
            <div class="swiper-button-prev" style="color:#fff !important;"></div>
        </div>
    </section>

    <div class="dyat-grid">
        <?php
        $args = array('post_type' => 'nick-pubg', 'posts_per_page' => 12, 'meta_query' => array(array('key' => 'is_sold', 'value' => 'no', 'compare' => '=')));
        $query = new WP_Query($args);
        if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
            $price_origin = (int)get_field('gia_ban'); $price_sale = (int)get_field('gia_sale');
        ?>
        <div class="dyat-card">
            <a href="<?php the_permalink(); ?>" class="card-link-overlay"></a>
            <div class="card-media">
                <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                <div class="ms-tag">MS: #<?php the_ID(); ?></div>
            </div>
            <div class="card-info">
                <h3 class="card-name"><?php the_title(); ?></h3>

                <div class="card-meta-bits">
                    <div class="meta-bit">
                        <i class="fa-solid fa-gun"></i> Skins:
                        <strong><?php echo get_field('skin_sung_count') ?: '0'; ?></strong>
                    </div>
                    <div class="meta-bit">
                        <i class="fa-solid fa-shirt"></i> Áo:
                        <strong><?php echo get_field('skin_ao_count') ?: '0'; ?></strong>
                    </div>
                    <div class="meta-bit">
                        <i class="fa-solid fa-star"></i> Lv:
                        <strong><?php echo get_field('level_acc') ?: '0'; ?></strong>
                    </div>
                    <div class="meta-bit">
                        <i class="fa-solid fa-trophy"></i> Rank:
                        <strong><?php echo get_field('rank_pubg') ?: '---'; ?></strong>
                    </div>
                </div>

                <div class="price-section">
                    <?php if ($price_sale > 0) : ?>
                    <span class="old-price"><?php echo number_format($price_origin); ?>đ</span>
                    <div class="price-ribbon"><?php echo number_format($price_sale); ?>đ</div>
                    <?php else : ?>
                    <div class="price-ribbon"><?php echo number_format($price_origin); ?>đ</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper(".mainSwiper", {
        loop: true,
        autoplay: {
            delay: 4000
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });
});
</script>
<?php get_footer(); ?>