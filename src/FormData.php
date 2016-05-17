<?php
/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BetterTaxonomyDescription;

use WP_Post;

/**
 * Used by Renderer and AdminForm classes to, respectively, retrieve and set data for post
 * rendering.
 *
 * @package BetterTaxonomyDescription
 */
class FormData
{
    /**
     * @var string
     */
    private static $nonce = 'plugin_boilerplate_render';

    /**
     * @var \BetterTaxonomyDescription\PostProvider
     */
    private $postProvider;

    /**
     * @param \BetterTaxonomyDescription\PostProvider $postProvider
     */
    public function __construct(PostProvider $postProvider = null)
    {
        $this->postProvider = $postProvider;
    }

    /**
     * Return the data to be passed to javascript and be used to translate UI and send AJAX request.
     *
     * @return array
     */
    public function data()
    {
        $post = $this->postProvider instanceof PostProvider ? $this->postProvider->get() : null;

        if ($post instanceof WP_Post) {
            return [
                'i18n' => [
                    'buttonTitle'  => __('Render Shortcodes', 'plugin_boilerplate'),
                    'noShortcodes' => __('No shortcodes to render.', 'plugin_boilerplate'),
                    'restore'      => __('Restore.', 'plugin_boilerplate'),
                    'notice'       => __(
                        'Shortcodes rendered. Save post to make changes effective.',
                        'plugin_boilerplate'
                    ),
                    'ajaxError'    => __(
                        'Sorry, an error occurred while rendering shortcodes.',
                        'plugin_boilerplate'
                    ),
                    'conf1'        => __('Content has changed since last rendering.', 'plugin_boilerplate'),
                    'conf2'        => __('By restoring you will lost those changes.', 'plugin_boilerplate'),
                    'confAll'      => __('Do you want to render ALL shortcodes?', 'plugin_boilerplate'),
                ],
                'data' => [
                    'pid'       => absint($post->ID),
                    'type'      => filter_var($post->post_type, FILTER_SANITIZE_STRING),
                    'plugin_boilerplateCheck' => wp_create_nonce($this->nonceAction($post->ID)),
                ]
            ];
        }
    }

    /**
     * Verify that $_POST data for a post and that current user has the capability to edit the post.
     *
     * @return bool|array
     */
    public function check()
    {
        $data = filter_input_array(INPUT_POST, [
            'pid'       => FILTER_SANITIZE_NUMBER_INT,
            'type'      => FILTER_SANITIZE_STRING,
            'plugin_boilerplateCheck' => FILTER_SANITIZE_STRING,
            'content'   => FILTER_REQUIRE_SCALAR
        ]);

        return
            wp_verify_nonce($data['plugin_boilerplateCheck'], $this->nonceAction($data['pid']))
            && $this->userCan($data['pid'], $data['type'])
            && ! empty($data['content'])
                ? $data
                : false;
    }

    private function nonceAction($id)
    {
        return self::$nonce.md5($id.LOGGED_IN_SALT.get_current_blog_id());
    }

    private function userCan($id, $type)
    {
        $postType = get_post_type_object($type);

        return is_object($postType) && current_user_can($postType->cap->edit_post, $id);
    }
}
