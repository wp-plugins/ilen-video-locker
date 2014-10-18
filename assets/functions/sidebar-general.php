<div class="infobox">
  <div class="ibox">
     
    <!--<div>
      <ul class="featured-buy">
        <li><i class="fa fa-heart-o"></i> Stats per post</li>
        <li><i class="fa fa-heart-o"></i> Stats per post  Stats per post</li>
        <li><i class="fa fa-heart-o"></i> Stats per post</li>
        <li><i class="fa fa-heart-o"></i> Stats per post</li>
        <li><i class="fa fa-heart-o"></i> Stats per post</li>
      </ul>
    </div>-->
    <!--<div style="margin: 0 auto;text-align: center;">
      <a rel="outline-inward" class="button outline-inward" data-toggle="popover" 
        data-content="And here's some amazing content. It's very engaging. right?">Get 100%</a>
    </div>-->

    <br />
    <?php if( (isset($_GET["tabs"]) && $_GET["tabs"] == "general") || (!isset($_GET["tabs"])) ): ?>
      <p>
        <strong style="font-weight:bold;">Tab General:</strong>
        <ul>
          <li><code>Width:</code> Fixed width in pixels of the video.</li>
          <li><code>Height:</code> Fixed height in pixels of the video.</li>
          <li><code>Video text:</code> Text displayed when clicking the video, the text is informative and has to be motivating for visitors to share the video.</li>
          <li><code>Use thumbnail Youtube?:</code> Use Youtube thumbnail in your post or photo video.</li>
        </ul>
        
      </p>
      <br />
      <h4 style="color:#333">Use the best 'Related Posts'</h4>
      <a href="https://wordpress.org/plugins/yuzo-related-post/" target="_blank"><img src="//ps.w.org/yuzo-related-post/assets/banner-772x250.jpg?rev=940528" width="100%" /></a>
    <?php endif; ?>
  </div>
</div>