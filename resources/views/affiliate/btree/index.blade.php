@extends('affiliate.layouts.main')

@section('main_content')

@php
    $startLevel = 1;
@endphp

<div class="m-content">
    <div class="row">
        <div class="col">
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                B-Tree
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-top:15px;">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">My organization</h3>

                            <div class="content-wrap">
                                <div class="left-sidebar">
                                    <div class="legend-wrap">
                                        <div class="legend-header">
                                            <div class="circle-btn">
                                                <?php echo file_get_contents('./assets/images/right_arrow.svg'); ?>
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
                                        @for ($i = 0; $i < 5; $i++)
                                            <div class="level level-{{ $i }}">
                                                <span class="level-label">Level {{ $i + $startLevel }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="tree-wrap">
                                        <div class="tree-level tree-level-0">
                                            <div class="distributor-wrap active elite-class black-diamond">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                        </div>
                                        <div class="tree-level tree-level-1">
                                            <div class="distributor-wrap active elite-class double-crown-diamond">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                            <div class="distributor-wrap active business-class sapphire-ambassador">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                        </div>
                                        <div class="tree-level tree-level-2">

                                            <div class="distributor-wrap open-position">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack"></div>
                                                </div>
                                                <span class="name">Open Position</span>
                                                <span class="tca-number"></span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                            <div class="distributor-wrap inactive coach-class senior-director">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>

                                            <div class="distributor-wrap inactive coach-class blue-diamond">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                            <div class="distributor-wrap active first-class executive">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                      <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                    </div>
                                                    <div class="selected-pack">
                                                      <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                    </div>
                                                </div>
                                                <span class="name">Pete Smith</span>
                                                <span class="tca-number">TSA1234567</span>
                                                <div class="vertical-line"></div>
                                                <div class="arc"></div>
                                            </div>
                                        </div>
                                        <div class="tree-level tree-level-3">
                                            <div class="four-distributors-wrap">
                                                <div class="distributor-wrap pending-position">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack"></div>
                                                    </div>
                                                    <span class="name">Pending Position</span>
                                                    <span class="tca-number"></span>
                                                </div>
                                                <div class="distributor-wrap pending-position">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack"></div>
                                                    </div>
                                                    <span class="name">Pending Position</span>
                                                    <span class="tca-number"></span>
                                                </div>

                                                <div class="distributor-wrap open-position">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack"></div>
                                                    </div>
                                                    <span class="name">Open Position</span>
                                                    <span class="tca-number"></span>
                                                </div>
                                                <div class="distributor-wrap open-position">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack"></div>
                                                    </div>
                                                    <span class="name">Open Position</span>
                                                    <span class="tca-number"></span>
                                                </div>
                                            </div>
                                            <div class="four-distributors-wrap">
                                                <div class="distributor-wrap inactive coach-class senior-director">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack">
                                                          <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                        </div>
                                                    </div>
                                                    <span class="name">Pete Smith</span>
                                                    <span class="tca-number">TSA1234567</span>
                                                </div>
                                                <div class="distributor-wrap open-position">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack"></div>
                                                    </div>
                                                    <span class="name">Open Position</span>
                                                    <span class="tca-number"></span>
                                                </div>

                                                <div class="distributor-wrap active coach-class director">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack">
                                                          <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                        </div>
                                                    </div>
                                                    <span class="name">Pete Smith</span>
                                                    <span class="tca-number">TSA1234567</span>
                                                </div>
                                                <div class="distributor-wrap active business-class ruby">
                                                    <div class="avatar-wrap">
                                                        <div class="avatar">
                                                          <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                        </div>
                                                        <div class="selected-pack">
                                                          <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                        </div>
                                                    </div>
                                                    <span class="name">Pete Smith</span>
                                                    <span class="tca-number">TSA1234567</span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="navigation-buttons">
                                            <div>
                                                <button class="button-up" type="button">
                                                  <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                                    Up one Level
                                                </button>
                                            </div>
                                            <div class="buttons-bottom">
                                                <button class="button-left" type="button">
                                                  <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                                    Bottom Left
                                                </button>
                                                <button class="button-right" type="button">
                                                  <?php echo file_get_contents('./assets/images/arrow.svg'); ?>
                                                    Bottom Right
                                                </button>
                                            </div>
                                        </div>
                                        <div class="info-wall">
                                            <div class="wall-header">
                                                <div class="rank-info">
                                                    <div class="title">Paid-As Rank</div>
                                                    <div class="value">Black Diamond</div>
                                                </div>
                                                <div class="percent-info">
                                                    <div class="title">Binary Paid</div>
                                                    <div class="value">10%</div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="wall-row">
                                                    <div class="label-column"></div>
                                                    <div class="leg-column">Left</div>
                                                    <div class="leg-column">Right</div>
                                                </div>
                                                <div class="wall-row">
                                                    <div class="label-column">Ambassadors</div>
                                                    <div class="leg-column">2</div>
                                                    <div class="leg-column">6</div>
                                                </div>
                                                <div class="wall-row">
                                                    <div class="label-column">Current Volume</div>
                                                    <div class="leg-column">10,000</div>
                                                    <div class="leg-column">200,000</div>
                                                </div>
                                                <div class="wall-row">
                                                    <div class="label-column">Carryover Volume</div>
                                                    <div class="leg-column">5,000</div>
                                                    <div class="leg-column">200,000</div>
                                                </div>
                                                <div class="wall-row">
                                                    <div class="label-column">Total Volume</div>
                                                    <div class="leg-column">15,000</div>
                                                    <div class="leg-column">400,000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="right-sidebar">
                                    <div class="search-wrap">
                                        <div class="search-header">
                                            <div class="circle-btn">
                                              <?php echo file_get_contents('./assets/images/right_arrow.svg'); ?>
                                            </div>
                                            <span class="title">Downline Search</span>
                                        </div>
                                        <div class="search-section">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade distributor-details" id="distributor-details" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ambassador Details</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="modal-avatar-wrap active business-class ruby">
                                                <div class="avatar">
                                                    <?php echo file_get_contents('./assets/images/user_btree.svg'); ?>
                                                </div>
                                                <div class="selected-pack">
                                                    <?php echo file_get_contents('./assets/images/logo_small.svg'); ?>
                                                </div>
                                            </div>
                                            <div class="details-wrap">
                                                <div class="details-row">
                                                    <div class="label">NAME</div>
                                                    <div class="value">Pete Smith</div>
                                                </div>
                                                <div class="details-row">
                                                    <div class="label">TSA#</div>
                                                    <div class="value">TSA1234567</div>
                                                </div>
                                                <div class="details-row">
                                                    <div class="label">Enrollment Date</div>
                                                    <div class="value">4/12/19</div>
                                                </div>
                                                <div class="details-row">
                                                    <div class="label">Sponsor</div>
                                                    <div class="value">Jesus Cardenas</div>
                                                </div>
                                                <div class="details-row">
                                                    <div class="label">PV</div>
                                                    <div class="value">5600</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
