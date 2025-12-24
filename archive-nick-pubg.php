<?php get_header(); ?>

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">

    <div class="shop-breadcrumb">
        <a href="<?php echo home_url(); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <span>Danh sách Nick PUBG</span>
    </div>

    <div class="mobile-filter-trigger" style="position: relative; z-index: 999;">
        <button id="btn-toggle-filter" type="button">
            <span><i class="fa-solid fa-filter"></i> LỌC SẢN PHẨM</span>
            <i class="fa-solid fa-chevron-down arrow-icon"></i>
        </button>
    </div>

    <div class="shop-filter-wrapper" id="filter-box">
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

            <div class="filter-item">
                <label><i class="fa-solid fa-gun"></i> SÚNG NÂNG CẤP</label>
                <select name="filter_gun">
                    <option value="">Tất cả</option>
                    <option value="yes" <?php selected($_GET['filter_gun'], "yes"); ?>>Có súng</option>
                    <option value="no" <?php selected($_GET['filter_gun'], "no"); ?>>Không súng</option>
                </select>
            </div>

            <div class="filter-item">
                <label><i class="fa-solid fa-globe"></i> SERVER</label>
                <select name="filter_server">
                    <option value="">Tất cả Server</option>
                    <option value="VN" <?php selected($_GET['filter_server'], "VN"); ?>>Việt Nam</option>
                    <option value="Quốc tế" <?php selected($_GET['filter_server'], "Quốc tế"); ?>>Quốc tế</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-submit-filter">LỌC SẢN PHẨM <i class="fa-solid fa-filter"></i></button>

                <?php if (!empty($_GET['filter_rank']) || !empty($_GET['filter_price']) || !empty($_GET['filter_gun']) || !empty($_GET['filter_server'])): ?>
                <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>" class="btn-reset-filter">
                    <i class="fa-solid fa-trash-can"></i> XÓA LỌC
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if (have_posts()) : ?>
    <div class="dyat-product-grid">
        <?php while (have_posts()) : the_post();
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
                    <div class="price-box">
                        <div class="sale-ribbon"><?php echo number_format((int)get_field('gia_ban')); ?>đ</div>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="btn-detail">CHI TIẾT</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
    <div class="empty-filter-result"
        style="text-align: center; padding: 100px 0; width: 100%; display: block; clear: both;">
        <i class="fa-solid fa-box-open" style="font-size: 80px; margin-bottom: 25px; color: #222; display: block;"></i>
        <h3 style="color: #fff; font-size: 20px; font-weight: 800; margin-bottom: 10px;">KHÔNG TÌM THẤY SẢN PHẨM</h3>
        <p style="color: #bbb; font-size: 15px; margin-bottom: 30px;">Rất tiếc, không có tài khoản nào phù hợp với yêu
            cầu của bạn.</p>
        <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>"
            style="background: #f39c12; color: #000; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: 800; font-size: 13px; display: inline-block; transition: 0.3s;">
            <i class="fa-solid fa-rotate-left"></i> QUAY LẠI TẤT CẢ TÀI KHOẢN
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('#btn-toggle-filter').on('click', function() {
        $('#filter-box').stop().slideToggle(300);
        $(this).find('.arrow-icon').toggleClass('fa-chevron-down fa-chevron-up');
    });
});
</script>

<?php get_footer(); ?>