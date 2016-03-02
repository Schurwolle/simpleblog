        
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
        @if($tags->count() > 0)
            <div class="right">
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