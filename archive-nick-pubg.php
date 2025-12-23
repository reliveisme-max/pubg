<?php get_header(); ?>

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">

    <div class="shop-breadcrumb">
        <a href="<?php echo home_url(); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <span>Danh sách Nick PUBG</span>
    </div>

    <div class="shop-filter-wrapper">
        <form action="" method="GET" class="filter-form">
            <div class="filter-item">
                <label><i class="fa-solid fa-trophy"></i> BẬC RANK</label>
                <select name="filter_rank">
                    <option value="">Tất cả Rank</option>
                    <?php
                    $ranks = ['Đồng', 'Bạc', 'Vàng', 'Bạch Kim', 'Kim Cương', 'Cao Thủ', 'Chí Tôn'];
                    foreach ($ranks as $rank): ?>
                        <option value="<?php echo $rank; ?>" <?php selected($_GET['filter_rank'], $rank); ?>>
                            <?php echo $rank; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-item">
                <label><i class="fa-solid fa-tags"></i> TẦM GIÁ</label>
                <select name="filter_price">
                    <option value="">Tất cả giá</option>
                    <option value="0-100000" <?php selected($_GET['filter_price'], "0-100000"); ?>>Dưới 100k</option>
                    <option value="100000-500000" <?php selected($_GET['filter_price'], "100000-500000"); ?>>100k - 500k
                    </option>
                    <option value="500000-1000000" <?php selected($_GET['filter_price'], "500000-1000000"); ?>>500k - 1M
                    </option>
                    <option value="1000000-999999999" <?php selected($_GET['filter_price'], "1000000-999999999"); ?>>
                        Trên 1M</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-submit-filter">LỌC SẢN PHẨM <i
                        class="fa-solid fa-magnifying-glass"></i></button>
                <?php if (isset($_GET['filter_rank']) || isset($_GET['filter_price'])): ?>
                    <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>" class="btn-reset-filter">XÓA LỌC</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="dyat-product-grid">
        <?php if (have_posts()) : while (have_posts()) : the_post();
                // Kiểm tra trạng thái đã bán để hiển thị logo
                $is_sold_acc = (get_field('is_sold') === 'yes' || get_field('is_sold') === 'Đã bán');
        ?>
                <div class="product-card <?php echo $is_sold_acc ? 'is-sold-acc' : ''; ?>">
                    <div class="card-thumb">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : the_post_thumbnail('medium');
                            else : ?>
                                <img src="https://via.placeholder.com/300x180" alt="No image">
                            <?php endif; ?>

                            <div class="card-ms">MS: #<?php the_ID(); ?></div>

                            <?php if ($is_sold_acc) : ?>
                                <div class="sold-badge-circle">ĐÃ BÁN</div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                        <div class="card-specs">
                            <div class="spec-item"><i class="fa-solid fa-trophy"></i> <?php the_field('rank_pubg'); ?></div>
                            <div class="spec-item"><i class="fa-solid fa-star"></i> Level: <?php the_field('level_acc'); ?>
                            </div>
                            <div class="spec-item"><i class="fa-solid fa-gun"></i> Súng: <?php the_field('skin_sung_count'); ?>
                            </div>
                            <div class="spec-item"><i class="fa-solid fa-shirt"></i> Áo: <?php the_field('skin_ao_count'); ?>
                            </div>
                        </div>

                        <div class="card-price-area">
                            <?php
                            $origin = (int)get_field('gia_ban');
                            $sale = (int)get_field('gia_sale');
                            ?>
                            <div class="price-box">
                                <?php if ($sale > 0) : ?>
                                    <span class="old-price"><?php echo number_format($origin); ?>đ</span>
                                    <div class="sale-ribbon"><?php echo number_format($sale); ?>đ</div>
                                <?php else : ?>
                                    <div class="sale-ribbon"><?php echo number_format($origin); ?>đ</div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>"
                                class="btn-detail"><?php echo $is_sold_acc ? 'XEM LẠI' : 'CHI TIẾT'; ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
    </div>

    <div class="shop-pagination">
        <?php
            echo paginate_links(array(
                'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
                'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
                'type'      => 'list',
            ));
        ?>
    </div>

<?php else : ?>
    <p style="color:#eee; text-align:center;">Không tìm thấy sản phẩm nào khớp với bộ lọc.</p>
<?php endif; ?>
</div>

<?php get_footer(); ?>