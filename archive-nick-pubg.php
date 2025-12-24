<?php get_header(); ?>

<div class="filter-overlay" id="filter-overlay"></div>

<div class="shop-filter-wrapper" id="filter-box">
    <div class="filter-mobile-header">
        <span><i class="fa-solid fa-sliders"></i> BỘ LỌC TÌM KIẾM</span>
        <i class="fa-solid fa-xmark" id="btn-close-filter"></i>
    </div>

    <form action="" method="GET" class="filter-form">
        <div class="filter-item">
            <label><i class="fa-solid fa-trophy"></i> BẬC RANK</label>
            <select name="filter_rank">
                <option value="">Tất cả Rank</option>
                <?php
                $ranks = ['Đồng', 'Bạc', 'Vàng', 'Bạch Kim', 'Kim Cương', 'Cao Thủ', 'Chí Tôn'];
                foreach ($ranks as $rank): ?>
                    <option value="<?php echo $rank; ?>"
                        <?php selected(isset($_GET['filter_rank']) ? $_GET['filter_rank'] : '', $rank); ?>>
                        <?php echo $rank; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-item">
            <label><i class="fa-solid fa-tags"></i> TẦM GIÁ</label>
            <select name="filter_price">
                <option value="">Tất cả giá</option>
                <option value="0-100000"
                    <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', "0-100000"); ?>>Dưới 100k
                </option>
                <option value="100000-500000"
                    <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', "100000-500000"); ?>>100k
                    - 500k</option>
                <option value="500000-1000000"
                    <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', "500000-1000000"); ?>>500k
                    - 1M</option>
                <option value="1000000-999999999"
                    <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', "1000000-999999999"); ?>>
                    Trên 1M</option>
            </select>
        </div>

        <div class="filter-item">
            <label><i class="fa-solid fa-gun"></i> SÚNG NÂNG CẤP</label>
            <select name="filter_gun">
                <option value="">Tất cả</option>
                <option value="yes" <?php selected(isset($_GET['filter_gun']) ? $_GET['filter_gun'] : '', "yes"); ?>>Có
                    súng</option>
                <option value="no" <?php selected(isset($_GET['filter_gun']) ? $_GET['filter_gun'] : '', "no"); ?>>Không
                    súng</option>
            </select>
        </div>

        <div class="filter-item">
            <label><i class="fa-solid fa-globe"></i> SERVER</label>
            <select name="filter_server">
                <option value="">Tất cả Server</option>
                <option value="VN"
                    <?php selected(isset($_GET['filter_server']) ? $_GET['filter_server'] : '', "VN"); ?>>Việt Nam
                </option>
                <option value="Quốc tế"
                    <?php selected(isset($_GET['filter_server']) ? $_GET['filter_server'] : '', "Quốc tế"); ?>>Quốc tế
                </option>
            </select>
        </div>

        <div class="filter-actions-group">
            <button type="submit" class="btn-submit-filter">ÁP DỤNG LỌC</button>
            <?php if (!empty($_GET['filter_rank']) || !empty($_GET['filter_price']) || !empty($_GET['filter_gun']) || !empty($_GET['filter_server'])): ?>
                <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>" class="btn-reset-filter">XÓA LỌC</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <div class="shop-breadcrumb">
        <a href="<?php echo home_url(); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-chevron-right separator"></i>
        <span>Danh sách Nick PUBG</span>
    </div>

    <div class="mobile-filter-trigger">
        <button id="btn-toggle-filter" type="button">
            <span><i class="fa-solid fa-filter"></i> LỌC SẢN PHẨM</span>
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <?php if (have_posts()) : ?>
        <div class="dyat-product-grid">
            <?php while (have_posts()) : the_post();
                $is_sold_acc = (get_field('is_sold') === 'yes' || get_field('is_sold') === 'Đã bán');
                $price_origin = (int)get_field('gia_ban');
                $price_sale   = (int)get_field('gia_sale');
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
                            <div class="spec-item"><i class="fa-solid fa-star"></i> Lv: <?php the_field('level_acc'); ?></div>
                        </div>
                        <div class="card-price-area">
                            <div class="price-box">
                                <?php if ($price_sale > 0) : ?>
                                    <span class="old-price"><?php echo number_format($price_origin); ?>đ</span>
                                    <div class="sale-ribbon"><?php echo number_format($price_sale); ?>đ</div>
                                <?php else : ?>
                                    <div class="sale-ribbon"><?php echo number_format($price_origin); ?>đ</div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>"
                                class="btn-detail"><?php echo $is_sold_acc ? 'XEM' : 'CHI TIẾT'; ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="shop-pagination">
            <?php echo paginate_links(array('prev_text' => '<i class="fa-solid fa-chevron-left"></i>', 'next_text' => '<i class="fa-solid fa-chevron-right"></i>', 'type' => 'list')); ?>
        </div>
    <?php else : ?>
        <div class="empty-filter-result">
            <i class="fa-solid fa-box-open"></i>
            <h3>KHÔNG TÌM THẤY SẢN PHẨM</h3>
            <p>Rất tiếc, không có tài khoản phù hợp với yêu cầu của bạn.</p>
            <a href="<?php echo get_post_type_archive_link('nick-pubg'); ?>">QUAY LẠI TẤT CẢ TÀI KHOẢN</a>
        </div>
    <?php endif; ?>
</div>

<script>
    jQuery(document).ready(function($) {
        function openF() {
            $('#filter-box').addClass('active');
            $('#filter-overlay').fadeIn(300);
            $('body').css('overflow', 'hidden');
        }

        function closeF() {
            $('#filter-box').removeClass('active');
            $('#filter-overlay').fadeOut(300);
            $('body').css('overflow', 'auto');
        }
        $('#btn-toggle-filter').on('click', openF);
        $('#btn-close-filter, #filter-overlay').on('click', closeF);
    });
</script>
<?php get_footer(); ?>