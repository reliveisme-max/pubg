<?php get_header(); ?>

<div class="container" style="margin-top: 30px; padding-bottom: 80px;">
    <div class="section-title-wrapper"
        style="margin-bottom: 40px; border-bottom: 1px solid #333; padding-bottom: 20px;">
        <h2 style="color: #fff; text-transform: uppercase; font-family: 'Rajdhani', sans-serif; font-size: 28px;">
            <i class="fas fa-search" style="color: var(--color-accent);"></i>
            Kết quả tìm kiếm cho: <span style="color: var(--color-accent);"><?php echo get_search_query(); ?></span>
        </h2>
        <p style="color: #888; font-size: 14px;">Tìm thấy <?php echo $wp_query->found_posts; ?> tài khoản phù hợp.</p>
    </div>

    <?php if (have_posts()) : ?>
        <div class="dyat-grid">
            <?php while (have_posts()) : the_post(); ?>
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
            <?php endwhile; ?>
        </div>

        <div class="shop-pagination" style="margin-top: 40px; text-align: center;">
            <?php
            echo paginate_links(array(
                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                'next_text' => '<i class="fas fa-chevron-right"></i>',
            ));
            ?>
        </div>

    <?php else : ?>
        <div class="no-results" style="text-align: center; padding: 100px 0;">
            <img src="https://pubg.shoprito.com/assets/img/no-results.png" alt="No results"
                style="max-width: 200px; opacity: 0.5; margin-bottom: 20px;">
            <h3 style="color: #fff;">Rất tiếc, không tìm thấy tài khoản nào!</h3>
            <p style="color: #888;">Bạn hãy thử tìm kiếm với từ khóa khác hoặc mã số khác xem sao.</p>
            <a href="<?php echo home_url(); ?>" class="btn-deposit" style="display: inline-block; margin-top: 20px;">QUAY
                LẠI TRANG CHỦ</a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>