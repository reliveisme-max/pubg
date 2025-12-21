<?php get_header(); ?>

<div class="container main-layout">
    <aside class="sidebar">
        <div class="filter-box">
            <h3><i class="fad fa-filter"></i> BỘ LỌC</h3>
            <div class="filter-group">
                <label>Mã số nick</label>
                <input type="text" placeholder="Tìm theo MS...">
            </div>
            <div class="filter-group">
                <label>Mức giá</label>
                <select>
                    <option>Tất cả giá</option>
                    <option>Dưới 100k</option>
                    <option>100k - 500k</option>
                </select>
            </div>
        </div>
    </aside>

    <main class="content">
        <div class="nick-grid">
            <?php
            $query = new WP_Query(array('post_type' => 'nick-pubg', 'posts_per_page' => 12));
            if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

            <div class="pubg-card">
                <div class="card-thumb">
                    <?php if(has_post_thumbnail()): the_post_thumbnail('medium'); else: ?>
                    <img src="https://via.placeholder.com/300x180/1a1d23/ffffff?text=PUBG+MOBILE" alt="No image">
                    <?php endif; ?>
                    <div class="card-ms">#<?php the_ID(); ?></div>
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?php the_title(); ?></h3>
                    <div class="card-stats">
                        <div class="stat-item"><i class="fad fa-trophy"></i>
                            <span><?php the_field('rank_pubg'); ?></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="price"><?php echo number_format(get_field('gia_ban')); ?>đ</div>
                        <a href="<?php the_permalink(); ?>" class="btn-premium">XEM CHI TIẾT</a>
                    </div>
                </div>
            </div>

            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>