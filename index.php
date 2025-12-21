<?php get_header(); ?>

<section class="hero-section">
    <div class="container">
        <h1>PUBG MOBILE MARKET</h1>
        <p>Hệ thống mua bán Nick PUBG tự động, uy tín bậc nhất Việt Nam</p>
    </div>
</section>

<div class="container">
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
                        <div class="ms-tag">#<?php the_ID(); ?></div>
                    </div>

                    <div class="card-info">
                        <div class="info-top">
                            <h3 class="card-name"><?php the_title(); ?></h3>
                            <i class="fa-regular fa-heart heart-icon"></i>
                        </div>

                        <div class="bid-label">Giá niêm yết</div>

                        <div class="card-action-row">
                            <div class="price-pill">
                                <i class="fas fa-bolt"></i>
                                <span><?php echo number_format(get_field('gia_ban')); ?>đ</span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn-bid">XEM <i class="fas fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>

        <?php endwhile;
            wp_reset_postdata();
        else: echo '<p>Chưa có sản phẩm nào.</p>';
        endif; ?>
    </div>
</div>

<?php get_footer(); ?>