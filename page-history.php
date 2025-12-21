<?php
/* Template Name: Lịch sử mua hàng */
get_header();
?>

<div class="container" style="margin-top: 50px; min-height: 600px;">
    <h2 style="font-family: 'Rajdhani'; font-size: 32px; color: var(--dyat-accent);">TÀI KHOẢN ĐÃ SỞ HỮU</h2>

    <?php
    $args = array(
        'post_type' => 'nick-pubg',
        'post_status' => 'any',
        'meta_query' => array(
            array('key' => 'buyer_id', 'value' => get_current_user_id(), 'compare' => '=')
        )
    );
    $history = new WP_Query($args);

    if ($history->have_posts()) : ?>
        <table
            style="width: 100%; background: var(--dyat-card); border-radius: 15px; border-collapse: collapse; overflow: hidden; margin-top: 30px;">
            <thead>
                <tr style="background: rgba(255,255,255,0.03); text-align: left;">
                    <th style="padding: 20px;">ID</th>
                    <th style="padding: 20px;">Tài khoản</th>
                    <th style="padding: 20px;">Mật khẩu</th>
                    <th style="padding: 20px;">Ngày sở hữu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($history->have_posts()) : $history->the_post(); ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 20px;">#<?php the_ID(); ?></td>
                        <td style="padding: 20px; color: var(--dyat-accent); font-weight: 700;"><?php the_field('tai_khoan'); ?>
                        </td>
                        <td style="padding: 20px; color: var(--dyat-accent); font-weight: 700;"><?php the_field('mat_khau'); ?>
                        </td>
                        <td style="padding: 20px; color: #888;"><?php echo get_the_modified_date('d/m/Y'); ?></td>
                    </tr>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="color: #888;">Bạn chưa sở hữu sản phẩm nào.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>