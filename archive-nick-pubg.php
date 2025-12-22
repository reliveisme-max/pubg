<?php get_header(); ?>

<section class="hero-section" style="padding: 40px 0 20px;">
    <div class="container">
        <h1 style="font-size: 32px;">DANH SÁCH NICK PUBG</h1>
        <p style="color: #888;">Tìm kiếm tài khoản phù hợp với túi tiền và cấp bậc của bạn</p>
    </div>
</section>

<div class="container">
    <div class="dyat-main-layout">
        <aside class="dyat-sidebar">
            <div class="filter-card">
                <h3><i class="fas fa-filter"></i> BỘ LỌC</h3>
                <form action="<?php echo get_post_type_archive_link('nick-pubg'); ?>" method="GET">

                    <div class="filter-group">
                        <label>Hạng (Rank)</label>
                        <select name="rank">
                            <option value="">Tất cả Rank</option>
                            <?php 
                            $ranks = ['Đồng', 'Bạc', 'Vàng', 'Bạch Kim', 'Kim Cương', 'Chí Tôn'];
                            foreach($ranks as $r): ?>
                            <option value="<?php echo $r; ?>"
                                <?php echo (isset($_GET['rank']) && $_GET['rank'] == $r) ? 'selected' : ''; ?>>
                                <?php echo $r; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Khoảng giá</label>
                        <select name="price_range">
                            <option value="">Tất cả mức giá</option>
                            <option value="0-500000"
                                <?php echo (@$_GET['price_range'] == '0-500000') ? 'selected' : ''; ?>>Dưới 500k
                            </option>
                            <option value="500000-2000000"
                                <?php echo (@$_GET['price_range'] == '500000-2000000') ? 'selected' : ''; ?>>500k - 2tr
                            </option>
                            <option value="2000000-999999999"
                                <?php echo (@$_GET['price_range'] == '2000000-999999999') ? 'selected' : ''; ?>>Trên 2tr
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">ÁP DỤNG</button>
                    <?php if(!empty($_GET)): ?>
                    <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>" class="btn-reset">Xóa lọc</a>
                    <?php endif; ?>
                </form>
            </div>
        </aside>

        <div class="dyat-content">
            <div class="dyat-grid">
                <?php
                $meta_query = array('relation' => 'AND');
                $meta_query[] = array('key' => 'is_sold', 'value' => 'no', 'compare' => '=');

                if (!empty($_GET['rank'])) {
                    $meta_query[] = array('key' => 'rank_pubg', 'value' => $_GET['rank'], 'compare' => '=');
                }

                if (!empty($_GET['price_range'])) {
                    $range = explode('-', $_GET['price_range']);
                    $meta_query[] = array('key' => 'gia_ban', 'value' => array($range[0], $range[1]), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
                }

                $args = array(
                    'post_type' => 'nick-pubg',
                    'posts_per_page' => 12,
                    'meta_query' => $meta_query,
                    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                );

                $query = new WP_Query($args);
                if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

                <div class="dyat-card">
                    <div class="card-media">
                        <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); endif; ?>
                        <div class="ms-tag">MS: #<?php the_ID(); ?></div>
                    </div>
                    <div class="card-info">
                        <div class="info-top">
                            <h3 class="card-name"><?php the_title(); ?></h3>
                        </div>
                        <div class="card-meta-bits">
                            <span>Rank: <strong><?php the_field('rank_pubg'); ?></strong></span>
                        </div>
                        <div class="card-action-row">
                            <div class="price-pill">
                                <span><?php echo number_format(get_field('gia_ban')); ?>đ</span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn-bid-sm">XEM</a>
                        </div>
                    </div>
                </div>

                <?php endwhile; wp_reset_postdata(); else: ?>
                <div class="not-found">Không có sản phẩm nào khớp với bộ lọc.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>