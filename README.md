# ExtraChildTheme
Extra Child Theme

## How to customize the Category from which posts will appear in the Trending Bar
1. open `functions.php` file from inside the child theme folder
2. go to line 51 where we have this PHP code:
```
$trending_posts = new WP_Query( apply_filters( 'extra_trending_posts_query', array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => '3',
		'cat' 			 => get_cat_ID('Video Posts'),
		'orderby'        => 'comment_count',
		'order'          => 'DESC',
	) ) );
```
`'post_type'      => 'post',` - will show only post types

`'posts_per_page' => '3',` - how many posts the trending bar should display

`'cat' 			 => get_cat_ID('Video Posts'),` - show only posts from the *Video Posts* category - change this to match your category, or delete that line so that the most 3 posts will be shown.

`'orderby'        => 'comment_count',` - the ordering criteria - by default the post will be ordered in DESC order based on the number of comments.

Happy Blogging!