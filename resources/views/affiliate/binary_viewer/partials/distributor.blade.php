@php
    $rankClasses = [
        \App\RankInterface::RANK_AMBASSADOR => 'ambassador',
        \App\RankInterface::RANK_DIRECTOR => 'director',
        \App\RankInterface::RANK_SENIOR_DIRECTOR => 'senior-director',
        \App\RankInterface::RANK_EXECUTIVE => 'executive',
        \App\RankInterface::RANK_SAPPHIRE_AMBASSADOR => 'sapphire-ambassador',
        \App\RankInterface::RANK_RUBY => 'ruby',
        \App\RankInterface::RANK_EMERALD => 'emerald',
        \App\RankInterface::RANK_DIAMOND => 'diamond',
        \App\RankInterface::RANK_BLUE_DIAMOND => 'blue-diamond',
        \App\RankInterface::RANK_BLACK_DIAMOND => 'black-diamond',
        \App\RankInterface::RANK_PRESIDENTIAL_DIAMOND => 'presidential-diamond',
        \App\RankInterface::RANK_CROWN_DIAMOND => 'crown-diamond',
        \App\RankInterface::RANK_DOUBLE_CROWN_DIAMOND => 'double-crown-diamond',
        \App\RankInterface::RANK_TRIPLE_CROWN_DIAMOND => 'triple-crown-diamond',
    ];

    $packClasses = [
        \App\Product::ID_NCREASE_ISBO => 'standby-class',
        \App\Product::ID_BASIC_PACK => 'coach-class',
        \App\Product::ID_VISIONARY_PACK => 'business-class',
        \App\Product::ID_FIRST_CLASS => 'first-class',
        \App\Product::ID_EB_FIRST_CLASS => 'first-class',
        \App\Product::ID_Traverus_Grandfathering => 'business-class',
        \App\Product::ID_PREMIUM_FIRST_CLASS => 'elite-class',
        \App\Product::ID_VIBE_OVERDRIVE_USER => 'vibe-overdrive-class',
    ]

@endphp

@if(empty($node) || empty($node->user))
    @include ('affiliate.binary_viewer.partials.open_position')
@else
    <div class="distributor-wrap
        {{ $node->user->getCurrentActiveStatus() ? 'active' : 'inactive' }}
        {{ $rankClasses[$node->user->rank()->rankid] }}
        {{ array_key_exists($node->user->current_product_id, $packClasses) ? $packClasses[$node->user->current_product_id] : 'No Product'}}"
    >
        <div class="avatar-wrap" data-href="{{ route('binaryViewer', ['id' => $node->id]) }}">
            <div class="avatar">
                <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
            </div>
            <div class="selected-pack">
                <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
            </div>
            <div class="distributor-details">
                <div class="details-wrap">
                    <div class="details-title">Details</div>
                    <div class="details-row">
                        <div class="label">NAME</div>
                        <div class="value">{{ $node->user->getFullName() }}</div>
                    </div>
                    <div class="details-row">
                        <div class="label">TSA#</div>
                        <div class="value">{{ $node->user->distid }}</div>
                    </div>
                    <div class="details-row">
                        <div class="label">Enrollment Date</div>
                        <div class="value">{{ $node->getEnrollmentDate() }}</div>
                    </div>
                    <div class="details-row">
                        <div class="label">Sponsor</div>
                        <div class="value">{{ $node->user && $node->user->sponsor ? $node->user->sponsor->getFullName() : 'No Sponsor' }}</div>
                    </div>
                    <div class="details-row">
                        <div class="label">PV</div>
                        <div class="value">{{ $node->user->current_month_pqv }}</div>
                    </div>
                </div>
                <div class="horizontal-line"></div>
                <div class="sloping-line"></div>
            </div>
        </div>
        <span class="name">{{ $node->user->getFullName() }}</span>
        <span class="tca-number">{{ $node->user->distid }}</span>
        <div class="vertical-line"></div>
        <div class="arc"></div>
    </div>
@endif




