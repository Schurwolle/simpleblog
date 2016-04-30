        
        <div class="left">
            @if ($users->count() > 0)
                <div style="border: 3px solid #73AD21;padding: 10px;background-color: lightgray;">
                    <h4> Browse articles by author: </h4>
                    <br>
                    @foreach($users as $user)
                        @if($user->articles->count() > 0)
                            <a href="/{{$user->name}}/articles"><button class="btn btn-default btn-sm">{{$user->name}}</button></a>
                        @endif
                    @endforeach
                </div>
            @endif
            <br>
            <br>
            <div class="row" style="width:100%;">
                {!! Form::open(array('url' => 'search')) !!}
                    <div class="form-group col-xs-10" style="padding-right: 0;">
                    {!! Form::text('search', null,
                                                [
                                                'class'         => 'form-control',
                                                'placeholder'   => 'Search',
                                                'id'            => 'search'
                                                ]) !!}
                    </div>
                    <div class="form-group col-xs-2" style="padding-left: 0;"> 
                     {!! Form::button('<i class="fa fa-search"></i>', 
                     [
                        'class'=> 'btn btn-default', 
                        'type' => 'submit',
                        'id'   => 'searchButton'
                     ]) !!}
                     </div>
                {!! Form::close() !!}
            </div>
        </div>
        
        
        <div class="right">
            @if($tags->count() > 0)
                <div style="border: 3px solid #73AD21;padding: 10px;background-color: lightgray;">
                    <h4>Browse articles by tag: </h4>
                    <br>
                    @foreach ($tags as $tag)
                        @if($tag->articles->count() > 0)
                            <a href="/tags/{{$tag->name}}"><button class="btn btn-default btn-sm">{{$tag->name}}</button></a>
                        @endif
                    @endforeach
                </div>
            @endif
            <br>
            <br>
            @if($articles->count() > 0)
                <div>
                    <div>
                        <h2 style="text-align: center;">Popular Articles</h2>
                    </div>
                    @foreach ($articles as $article)
                        @if (file_exists('pictures/'.$article->id.'thumbnail'))
                            <div id="popular">
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