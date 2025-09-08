@if ($menu_group)
    <nav class="{{ $menu_group->class }}" {!! $menu_group->style != null ? 'style="'.$menu_group->style.'"' : '' !!}>
        <ul>
            @foreach ($menu_group->firstLevelMenu() as $parentItem)
                @if ($parentItem->hasChilds())
                    <li class="has-children">
                        <a href="#">
                            <span>{{ $parentItem->displayName() }}</span>
                        </a>
                        <ul class="sub-menu">
                            @foreach ($parentItem->childList() as $child)
                                <li class="">
                                    <a href="{{ $child->link() }}">
                                        <span>{{ $child->displayName() }}</span>
                                    </a>

                                </li>
                            @endforeach
                        </ul>
                    </li>
                @elseif ($parentItem->hasMegaMenu())
                    <li class="has-megamenu">
                        <a href="#">
                            <span>{{ $parentItem->displayName() }}</span>
                        </a>
                        <ul class="mega-menu">

                            @foreach ($parentItem->megaMenuList() as $item)
                                @if ($item->isDefault())
                                    <li>
                                        <span class="mega-menu-title">{{ $item->displayName() }}</span>

                                        <ul class="sub-menu">
                                            @foreach ($item->defaultLinks() as $link)
                                                <li>
                                                    <a href="{{ $link->link }}">
                                                        <span>{{ $link->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif ($item->isCategories())
                                    <li>
                                        <span class="mega-menu-title">{{ $item->displayName() }}</span>

                                        <ul class="sub-menu">
                                            @foreach ($item->categories() as $category)
                                                <li>
                                                    <a href="{{ $category->productsLink() }}">
                                                        <span>{{ $category->label }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif ($item->isHtml())
                                    <li>
                                        <section class="promo-box">
                                            {!! html_entity_decode($item->html()) !!}
                                        </section>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="">
                        <a href="{{ $parentItem->link() }}">
                            <span>{{ $parentItem->displayName() }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
@endif
