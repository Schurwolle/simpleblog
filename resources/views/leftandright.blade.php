        
        @if ($users->count() > 0)
            <div class="left">
            <h4> Browse articles by author:
                <br>
                <br>
                @foreach($users as $user)
                    @if($user->articles->count() > 0)
                        <a href="/{{$user->name}}/articles"><button class="btn btn-default btn-sm">{{$user->name}}</button></a>
                    @endif
                @endforeach
            </div>
        @endif
        
        <div class="right">
            @if($tags->count() > 0)
                <div style="border: 3px solid #73AD21;padding: 10px;background-color: lightgray;">
                    <h4>Browse articles by tag:
                        <br>
                        <br>
                        @foreach ($tags as $tag)
                            @if($tag->articles->count() > 0)
                                <a href="/tags/{{$tag->name}}"><button class="btn btn-default btn-sm">{{$tag->name}}</button></a>
                            @endif
                        @endforeach
                    </h4>
                </div>
            @endif
            <br>
            <br>
            @if($articles->count() > 0)
                <div>
                    <div>
                        <h2 style="padding-left: 12px;">Popular Articles</h2>
                    </div>
                    @foreach ($articles as $article)
                        @if (file_exists('pictures/'.$article->id.'thumbnail'))
                            <div style="border: 3px solid #73AD21; padding: 10px; background-color:lightgray;">
                                <a href="/articles/{{$article->slug}}" style="color: black; font-weight: bold;">{{$article->title}}</a>
                                <a href="/articles/{{$article->slug}}">{{ Html::image(('pictures/'.$article->id.'thumbnail'), null) }}
                                </a>   
                            </div>
                        @endif
                    @endforeach        
                </div>
            @endif
            <br>
            <br>
        </div>
        