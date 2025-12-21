<?php get_header(); ?>

<div class="container">
    <div class="main-layout">
        <aside class="sidebar">
            <div class="filter-box">
                <h3 style="color: var(--primary); margin-top: 0;">BỘ LỌC</h3>
                <p style="font-size: 14px; color: #888;">Lọc theo giá, rank...</p>
            </div>
        </aside>

        <div class="content">
            <div class="nick-grid">
                <?php
                $query = new WP_Query(array('post_type' => 'nick-pubg', 'posts_per_page' => 12));
                if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

                        <div class="pubg-card">
                            <div class="card-thumb">
                                <img src="<?php the_post_thumbnail_url(); ?>" alt="">
                                <div class="card-ms">MS: <?php the_ID(); ?></div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php the_title(); ?></h3>
                                <div class="card-info">
                                    <div class="info-item">Rank: <strong><?php the_field('rank_pubg'); ?></strong></div>
                                    <div class="info-item">Skin: <strong>50+</strong></div>
                                </div>
                                <div class="card-footer">
                                    <div class="price"><?php echo number_format(get_field('gia_ban')); ?>đ</div>
                                    <a href="<?php the_permalink(); ?>" class="btn-buy">XEM</a>
                                </div>
                            </div>
                        </div>

                <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>