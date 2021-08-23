@extends('affiliate.layouts.app')

@section('content')

@php
    $levelOffset = $rootNode->depth;
    $startLevel = $startLevel = $currentNode->depth - $levelOffset;
@endphp

<div class="binary-tree-page">
    <div class="content-wrap">
        <div class="left-sidebar">
            <div class="legend-wrap">
                <div class="legend-header">
                    <div class="circle-btn">
                        <i class="la la-angle-right arrow"></i>
                    </div>
                    <span class="title">Legend</span>
                </div>
                <div class="legend">
                    <div class="list-title">Qualification:</div>
                    <ul class="list-wrap">
                        <li>
                            <div class="image active"></div>
                            <span>Active</span>
                        </li>
                        <li>
                            <div class="image inactive"></div>
                            <span>Inactive</span>
                        </li>
                    </ul>
                    <div class="list-title">Pack Selection:</div>
                    <ul class="list-wrap">
                        <li class="vibe-overdrive-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>Vibe Overdrive</span>
                        </li>
                        <li class="standby-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>Standby Class</span>
                        </li>
                        <li class="coach-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>Coach Class</span>
                        </li>
                        <li class="business-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>Business Class</span>
                        </li>
                        <li class="first-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>First Class</span>
                        </li>
                        <li class="elite-class">
                            <div class="image selected-pack"><?php echo file_get_contents('./assets/images/logo_small.svg'); ?></div>
                            <span>Elite Class</span>
                        </li>
                    </ul>
                    <div class="list-title">Ranks:</div>
                    <ul class="list-wrap">
                        <li>
                            <div class="image ambassador"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Ambassador</span>
                        </li>
                        <li>
                            <div class="image director"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Director</span>
                        </li>
                        <li>
                            <div class="image senior-director"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Senior Director</span>
                        </li>
                        <li>
                            <div class="image executive"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Executive</span>
                        </li>
                        <li>
                            <div class="image sapphire-ambassador"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Sapphire Ambassador</span>
                        </li>
                        <li>
                            <div class="image ruby"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Ruby</span>
                        </li>
                        <li>
                            <div class="image emerald"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Emerald</span>
                        </li>
                        <li>
                            <div class="image diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Diamond</span>
                        </li>
                        <li>
                            <div class="image blue-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Blue Diamond</span>
                        </li>
                        <li>
                            <div class="image black-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Black Diamond</span>
                        </li>
                        <li>
                            <div class="image presidential-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Presidential Diamond</span>
                        </li>
                        <li>
                            <div class="image crown-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Crown Diamond</span>
                        </li>
                        <li>
                            <div class="image double-crown-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Double Crown Diamond</span>
                        </li>
                        <li>
                            <div class="image triple-crown-diamond"><?php echo file_get_contents('./assets/images/user_btree.svg'); ?></div>
                            <span>Triple Crown Diamond</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="levels-wrap">
                @for ($i = 0; $i < 4; $i++)
                    <div class="level level-{{ $i }}">
                        <span class="level-label">Level {{ $i + $startLevel }}</span>
                    </div>
                @endfor
            </div>
        </div>
        <div class="content">
            <div class="tree-wrap">
                <h3 class="page-title">My Organization</h3>
                <div class="tree">
                    <div class="tree-level tree-level-0">
                        @include ('affiliate.binary_viewer.partials.distributor', ['node' => $currentNode])
                    </div>
                    <div class="tree-level tree-level-1">
                        @php
                            $n2 = !empty($currentNode) ? $currentNode->getLeftLeg() : null;
                            $n3 = !empty($currentNode) ? $currentNode->getRightLeg() : null;

                            $l1Nodes = [$n2, $n3];
                        @endphp

                        @foreach($l1Nodes as $l1Node)
                            @include ('affiliate.binary_viewer.partials.distributor', ['node' => $l1Node])
                        @endforeach
                    </div>
                    <div class="tree-level tree-level-2">
                        @php
                            $n4 = !empty($n2) ? $n2->getLeftLeg() : null;
                            $n5 = !empty($n2) ? $n2->getRightLeg() : null;
                            $n6 = !empty($n3) ? $n3->getLeftLeg() : null;
                            $n7 = !empty($n3) ? $n3->getRightLeg() : null;

                            $l2Nodes = [$n4, $n5, $n6, $n7];
                        @endphp

                        @foreach($l2Nodes as $l2Node)
                            @include ('affiliate.binary_viewer.partials.distributor', ['node' => $l2Node])
                        @endforeach

                    </div>
                    <div class="tree-level tree-level-3">
                        <div class="four-distributors-wrap">
                            @php
                                $n8 = !empty($n4) ? $n4->getLeftLeg() : null;
                                $n9 = !empty($n4) ? $n4->getRightLeg() : null;
                                $n10 = !empty($n5) ? $n5->getLeftLeg() : null;
                                $n11 = !empty($n5) ? $n5->getRightLeg() : null;

                                $l3Nodes1 = [$n8, $n9, $n10, $n11];
                            @endphp

                            @foreach($l3Nodes1 as $l3Node1)
                                @include ('affiliate.binary_viewer.partials.distributor', ['node' => $l3Node1])
                            @endforeach
                        </div>
                        <div class="four-distributors-wrap">
                            @php
                                $n12 = !empty($n6) ? $n6->getLeftLeg() : null;
                                $n13 = !empty($n6) ? $n6->getRightLeg() : null;
                                $n14 = !empty($n7) ? $n7->getLeftLeg() : null;
                                $n15 = !empty($n7) ? $n7->getRightLeg() : null;

                                $l3Nodes2 = [$n12, $n13, $n14, $n15];
                            @endphp

                            @foreach($l3Nodes2 as $l3Node2)
                                @include ('affiliate.binary_viewer.partials.distributor', ['node' => $l3Node2])
                            @endforeach
                        </div>
                    </div>
                    <div class="navigation-buttons">
                        <div>
                            <a href="{{ !empty($currentNode) && $currentNode->id !== $rootNode->id ? route('binaryViewer', ['id' => $rootNode->id]) : '#' }}"
                               class="btn button-up js-btn-up {{ !empty($currentNode) && ($currentNode->parent_id === null || $currentNode->id === $rootNode->id) ? 'disabled' : '' }}"
                            >
                                <?php echo file_get_contents('./assets/images/go_to_top_icon.svg'); ?>
                                Go to top
                            </a>
                        </div>
                        <div>
                            <a href="{{ !empty($currentNode) && $currentNode->parent_id !== null ? route('binaryViewer', ['id' => $currentNode->parent_id]) : '#' }}"
                               class="btn button-up js-btn-up {{ !empty($currentNode) && ($currentNode->parent_id === null || $currentNode->id === $rootNode->id) ? 'disabled' : '' }}"
                            >
                                <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                Up one Level
                            </a>
                        </div>
                        <div class="buttons-bottom">
                            <a href="{{ !empty($lastLeftNode) ? route('binaryViewer', ['id' => $lastLeftNode->id]) : '#' }}" class="btn button-left js-btn-left {{ empty($lastLeftNode) ? 'disabled' : '' }}">
                                <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                Bottom Left
                            </a>
                            <a href="{{ !empty($lastRightNode) ? route('binaryViewer', ['id' => $lastRightNode->id]) : '#' }}" class="btn button-right js-btn-right {{ empty($lastRightNode) ? 'disabled' : '' }}">
                                <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                Bottom Right
                            </a>
                        </div>
                    </div>
                    <div class="info-wall">
                        <div class="wall-header">
                            <div class="rank-info">
                                <div class="title">Paid-As Rank</div>
                                <div class="value">{{ $currentNode->user->getPaidRank()->rankdesc }}</div>
                            </div>
                            <div class="percent-info">
                                <div class="title">Binary Paid</div>
                                <div class="value">{{ $currentNode->user->getBinaryPaidPercent() * 100 }}%</div>
                            </div>
                        </div>
                        <div>
                            <div class="wall-row">
                                <div class="label-column"></div>
                                <div class="leg-column">LEFT</div>
                                <div class="leg-column">RIGHT</div>
                            </div>
                            <div class="wall-row">
                                <div class="label-column">Ambassadors</div>
                                <div class="leg-column">{{ $legend['left']->count() }}</div>
                                <div class="leg-column">{{ $legend['right']->count() }}</div>
                            </div>
                            <div class="wall-row">
                                <div class="label-column">Current Week</div>
                                <div class="leg-column">{{ $leftCurrentWeek }}</div>
                                <div class="leg-column">{{ $rightCurrentWeek }}</div>
                            </div>
                            <div class="wall-row">
                                <div class="label-column">Previous week</div>
                                <div class="leg-column">{{ $previousWeekTotal->left - $previousWeekCarryOvers['left'] }}</div>
                                <div class="leg-column">{{ $previousWeekTotal->right - $previousWeekCarryOvers['right'] }}</div>
                            </div>
                            <div class="wall-row">
                                <div class="label-column">Prev Week Carryover</div>
                                <div class="leg-column">{{ $previousWeekCarryOvers['left'] }}</div>
                                <div class="leg-column">{{ $previousWeekCarryOvers['right'] }}</div>
                            </div>
                            <div class="wall-row">
                                <div class="label-column">Total Previous Week</div>
                                <div class="leg-column">{{ $previousWeekTotal->left }}</div>
                                <div class="leg-column">{{ $previousWeekTotal->right }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Search
                v-bind:distributors="{{$distributors}}"
                v-bind:end="{{$distributorsEnd}}"
                v-bind:total="{{$distCount}}"
                v-bind:ranks="{{$ranks}}"
                v-bind:packs="{{$packs}}"
                v-bind:leg="{{$legKey}}"
                v-bind:node="{{$currentNode->id}}"
                v-bind:level="{{$rootNode->depth}}"
        ></Search>
    </div>
</div>

<script>
    var csrfToken = '{{ csrf_token() }}';
    var baseUrl = '{{url('/')}}';
</script>
@endsection

@section('scripts')
<script src="{{asset('/js/binary.viewer.js')}}" type="text/javascript"></script>
@endsection
