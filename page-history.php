<?php
/* Template Name: Lịch sử mua hàng */
get_header();
?>

<div class="container" style="margin-top: 50px; min-height: 600px; padding-bottom: 80px;">
    <div class="history-header">
        <h2 class="history-title">
            <i class="fa-duotone fa-solid fa-clock-rotate-left"></i> LỊCH SỬ SỞ HỮU NICK
        </h2>
        <p class="history-subtitle">Danh sách các tài khoản bạn đã mua trên hệ thống.</p>
    </div>

    <?php
    $current_user_id = get_current_user_id();
    $args = array(
        'post_type' => 'nick-pubg',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'buyer_id',
                'value' => $current_user_id,
                'compare' => '='
            )
        )
    );
    $history = new WP_Query($args);

    if ($history->have_posts()) : ?>
        <div class="history-table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Mã số</th>
                        <th>Thông tin tài khoản</th>
                        <th>Mật khẩu</th>
                        <th>Ngày mua</th>
                        <th style="text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($history->have_posts()) : $history->the_post(); ?>
                        <tr>
                            <td class="td-ms">#<?php the_ID(); ?></td>
                            <td>
                                <div class="account-cell">
                                    <span id="user-<?php the_ID(); ?>"><?php the_field('tai_khoan'); ?></span>
                                    <button class="btn-copy-mini" data-copy="user-<?php the_ID(); ?>" title="Copy tài khoản">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="account-cell">
                                    <span id="pass-<?php the_ID(); ?>"><?php the_field('mat_khau'); ?></span>
                                    <button class="btn-copy-mini" data-copy="pass-<?php the_ID(); ?>" title="Copy mật khẩu">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="td-date"><?php echo get_the_modified_date('H:i - d/m/Y'); ?></td>
                            <td style="text-align: right;">
                                <a href="<?php the_permalink(); ?>" class="btn-view-history">
                                    XEM LẠI <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="history-empty">
            <i class="fa-duotone fa-solid fa-box-open"></i>
            <h3>BẠN CHƯA CÓ GIAO DỊCH NÀO</h3>
            <p>Hãy chọn cho mình một chiếc nick thật xịn để bắt đầu trải nghiệm nhé!</p>
            <a href="<?php echo home_url('/nick-pubg/'); ?>" class="btn-go-shop">MUA NICK NGAY</a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>