<html>
    <head>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="container p-5">
            <h1 class="text-2xl mb-5">글목록</h1>
            <?php foreach($articles as $article):?>
                <div class="background-white border rounded mb-3 p-3">
                    <p><?php echo $article->body;?></p>
                    <p><?php echo $article->created_at;?></p>
                </div>
            <?php endforeach;?>
        </div>
    </body>
</html>