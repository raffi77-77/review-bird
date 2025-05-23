<?php
?>
<html>
<head>
    <style>
        @media only screen and (max-width: 600px) {
            table {
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px;">
<table cellpadding="0" cellspacing="0" border="0" width="100%"
       style="max-width: 600px; /*margin: auto;*/ background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
    <tr>
        <td style="padding: 20px 30px;">
            <h2 style="color: #111827; font-size: 20px; margin-bottom: 10px;">📝 <?= __( 'New Review Submitted', 'review-bird' ) ?></h2>
            <p style="color: #374151; margin-bottom: 20px; "><?= __( 'Find below the review details.', 'review-bird' ) ?></p>
            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="font-weight: bold; color: #374151;"><?= __( 'Flow:', 'review-bird' ) ?></td>
                    <td style="color: #111827;"><a href="<?= ! empty( $flow ) ? esc_url( get_edit_post_link( $flow->get_id() ) ) : '#' ?>"><?= esc_html( $flow->title ?? '' ) ?> 🔗</a></td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="font-weight: bold; color: #374151;"><?= __( 'Username:', 'review-bird' ) ?></td>
                    <td style="color: #111827;"><?= esc_html( $username ?? '' ) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="font-weight: bold; color: #374151;"><?= __( 'Review:', 'review-bird' ) ?></td>
                    <td style="color: #111827;"><?= nl2br( esc_html( $review_text ?? '' ) ) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="font-weight: bold; color: #374151;"><?= __( 'Rating:', 'review-bird' ) ?></td>
                    <td><?= esc_html( $stars_html ?? '' ) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #374151;"><?= __( 'Answer:', 'review-bird' ) ?></td>
                    <td><?= esc_html( $like_svg ?? '' ) ?></td>
                </tr>
            </table>
            <p style="font-size: 12px; color: #6b7280; margin-top: 20px;"><?= sprintf( __( 'This is an automated notification from %s.', 'review-bird' ), $site_name ?? '' ) ?></p>
        </td>
    </tr>
</table>
</body>
</html>