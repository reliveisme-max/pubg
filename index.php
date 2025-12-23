<?php get_header(); ?>

<div class="container">
    <section class="shoprito-slider">
        <div class="swiper mainSwiper">
            <div class="swiper-wrapper">
                <?php if (have_rows('home_slider', 'option')): ?>
                    <?php while (have_rows('home_slider', 'option')): the_row();
                        $img = get_sub_field('bg_image');
                        $link = get_sub_field('button_link'); ?>
                        <div class="swiper-slide banner-item">
                            <?php if ($link): ?><a href="<?php echo esc_url($link); ?>"><?php endif; ?>
                                <img src="<?php echo esc_url($img['url']); ?>" alt="Banner"
                                    style="width:100%; border-radius:12px; display:block;">
                                <?php if ($link): ?></a><?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <?php if (have_rows('home_banners', 'option')): ?>
        <div class="banner-grid-small">
            <?php while (have_rows('home_banners', 'option')): the_row();
                $img = get_sub_field('img_banner');
                $link = get_sub_field('link_banner'); ?>
                <div class="banner-col">
                    <a href="<?php echo $link ? esc_url($link) : '#'; ?>">
                        <?php if ($img): ?>
                            <img src="<?php echo esc_url($img['url']); ?>" alt="Banner">
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <?php if (have_rows('home_sections', 'option')): ?>
        <?php while (have_rows('home_sections', 'option')): the_row(); ?>
            <?php if (get_sub_field('show_sec')):
                $sec_title    = get_sub_field('sec_title');
                $sec_link     = get_sub_field('sec_link');
                $sec_products = get_sub_field('sec_products'); ?>
                <div class="home-section-wrapper" style="margin-top: 50px;">
                    <div class="section-header"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #333; padding-bottom: 15px;">
                        <h2 class="section-title"
                            style="margin: 0; font-size: 20px; font-weight: 800; color: #fff; text-transform: uppercase;">
                            <i class="fa-solid fa-gamepad" style="color: var(--color-accent); margin-right: 10px;"></i>
                            <?php echo esc_html($sec_title); ?>
                        </h2>
                        <?php if ($sec_link): ?>
                            <a href="<?php echo esc_url($sec_link); ?>"
                                style="color: var(--color-accent); font-size: 13px; text-decoration: none; font-weight: 600;">
                                Xem tất cả <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="dyat-grid">
                        <?php if ($sec_products): ?>
                            <?php foreach ($sec_products as $post): setup_postdata($post);
                                // Kiểm tra trạng thái bán
                                $is_sold_acc = (get_field('is_sold', $post->ID) === 'yes' || get_field('is_sold', $post->ID) === 'Đã bán');
                                $price_origin = (int)get_field('gia_ban');
                                $price_sale   = (int)get_field('gia_sale'); ?>
                                <div class="dyat-card <?php echo $is_sold_acc ? 'is-sold-acc' : ''; ?>">
                                    <a href="<?php the_permalink(); ?>" class="card-link-overlay"></a>
                                    <div class="card-media">
                                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large');
                                        endif; ?>
                                        <div class="ms-tag">MS: #<?php the_ID(); ?></div>

                                        <?php if ($is_sold_acc) : ?>
                                            <div class="sold-badge-circle">ĐÃ BÁN</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-info">
                                        <h3 class="card-name"><?php the_title(); ?></h3>
                                        <div class="card-meta-bits">
                                            <div class="meta-bit"><i class="fa-solid fa-trophy"></i> <?php the_field('rank_pubg'); ?></div>
                                            <div class="meta-bit"><i class="fa-solid fa-star"></i> Level: <?php the_field('level_acc'); ?>
                                            </div>
                                            <div class="meta-bit"><i class="fa-solid fa-gun"></i> Súng:
                                                <?php the_field('skin_sung_count'); ?></div>
                                            <div class="meta-bit"><i class="fa-solid fa-shirt"></i> Áo: <?php the_field('skin_ao_count'); ?>
                                            </div>
                                        </div>
                                        <div class="price-section">
                                            <?php if ($price_sale > 0): ?>
                                                <span class="old-price"><?php echo number_format($price_origin); ?>đ</span>
                                                <div class="price-ribbon"><?php echo number_format($price_sale); ?>đ</div>
                                            <?php else: ?>
                                                <div class="price-ribbon"><?php echo number_format($price_origin); ?>đ</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                            wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php if (get_field('show_all_products', 'option')): ?>
        <div class="section-header" style="margin-top: 60px;">
            <h2 class="section-title">
                <i class="fa-solid fa-list-ul"></i>
                <?php echo get_field('all_products_title', 'option') ?: 'TẤT CẢ TÀI KHOẢN'; ?>
            </h2>
        </div>

        <div class="dyat-grid">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type'      => 'nick-pubg',
                'posts_per_page' => 12,
                'paged'          => $paged,
                'meta_key'       => 'is_sold', // Sắp xếp theo trạng thái bán
                'orderby'        => 'meta_value',
                'order'          => 'ASC' // 'no' sẽ lên trước 'yes'
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    $is_sold_acc = (get_field('is_sold') === 'yes' || get_field('is_sold') === 'Đã bán');
                    $price_origin = (int)get_field('gia_ban');
                    $price_sale   = (int)get_field('gia_sale'); ?>
                    <div class="dyat-card <?php echo $is_sold_acc ? 'is-sold-acc' : ''; ?>">
                        <a href="<?php the_permalink(); ?>" class="card-link-overlay"></a>
                        <div class="card-media">
                            <?php if (has_post_thumbnail()) : the_post_thumbnail('large');
                            endif; ?>
                            <div class="ms-tag">MS: #<?php the_ID(); ?></div>

                            <?php if ($is_sold_acc) : ?>
                                <div class="sold-badge-circle">ĐÃ BÁN</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-info">
                            <h3 class="card-name"><?php the_title(); ?></h3>
                            <div class="card-meta-bits">
                                <div class="meta-bit"><i class="fa-solid fa-trophy"></i> <?php the_field('rank_pubg'); ?></div>
                                <div class="meta-bit"><i class="fa-solid fa-star"></i> Level: <?php the_field('level_acc'); ?></div>
                                <div class="meta-bit"><i class="fa-solid fa-gun"></i> Súng: <?php the_field('skin_sung_count'); ?>
                                </div>
                                <div class="meta-bit"><i class="fa-solid fa-shirt"></i> Áo: <?php the_field('skin_ao_count'); ?>
                                </div>
                            </div>
                            <div class="price-section">
                                <?php if ($price_sale > 0): ?>
                                    <span class="old-price"><?php echo number_format($price_origin); ?>đ</span>
                                    <div class="price-ribbon"><?php echo number_format($price_sale); ?>đ</div>
                                <?php else: ?>
                                    <div class="price-ribbon"><?php echo number_format($price_origin); ?>đ</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>

        <div class="shop-pagination" style="margin-top: 40px;">
            <?php
            echo paginate_links(array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => max(1, get_query_var('paged')),
                'total'     => $query->max_num_pages,
                'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
                'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
                'type'      => 'list',
            )); ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
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
        }
    });
</script>

<?php get_footer(); ?>