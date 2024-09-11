<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="select.css"> -->
<link rel="stylesheet" href="/wp-content/plugins/directory_plugin-master/admin_template/bootstrap.css">
<link rel="stylesheet" href="/wp-content/plugins/directory_plugin-master/admin_template/style.css">
<div class="container my-3">
  <div class="panel panel-default">
    <div class="panel-body">
      <ul class="nav nav-tabs nav-tabs-simple nav-tabs-simple-bottom" id="googleYelpDataContentTabs" role="tablist">
        <li class="nav-item active">
          <a class="nav-link" id="nav2-tab" data-toggle="tab" href="#nav2" role="tab">
            <b>New Listings Import</b>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav3-tab" data-toggle="tab" href="#nav3" role="tab">
            <b>Configurations</b>
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane show fade in active" id="nav2" role="tabpanel" aria-labelledby="nav2-tab">
          <div class="row">
            <div class="col-md-6">
              <div class="admin-table">
                <ul class="nav nav-tabs nav-tabs-simple nav-tabs-simple-bottom" id="listingsSearchContentTabs" role="tablist">
                  <li class="nav-item active">
                    <a class="nav-link active" id="listings-search-nearby-layout-tab" data-toggle="tab" href="#listings-search-nearby-layout" role="tab" aria-expanded="true">
                      <b>Nearby search</b>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="listings-search-text-layout-tab" data-toggle="tab" href="#listings-search-text-layout" role="tab">
                      <b>Text search</b>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="listings-search-outscraper-layout-tab" data-toggle="tab" href="#listings-search-outscraper-layout" role="tab">
                      <b>Outscraper</b>
                    </a>
                  </li>
                  <div class="clearfix"></div>
                </ul>
                <div class="tab-content" id="listingsSearchTabsContent" style="margin-bottom: 10px">
                  <div class="tab-pane fade active in" id="listings-search-nearby-layout" role="tabpanel" aria-labelledby="listings-search-nearby-layout-tab">
                    <form id="placesSearchNearbyForm">
                      <div class="row" style="margin-top: 15px">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" class="form-control places-keyword-filter" placeholder="Barber Shops" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="country form-control" placeholder="Type a country" value="" />
                            <!--
                              <select class='country select2'><option value=''>Select an option</option>
                                  " . listCountries() . "
                              </select>
                              -->
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>State</label>
                            <input type="text" class="state form-control" placeholder="Enter a state" />
                            <!--
                              <select class='state select2'><option value=''>Select an option</option></select>
                              -->
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>City</label>
                            <input type="text" class="city form-control" placeholder="Enter a city" />
                            <!--
                              <select class='city select2'><option value=''>Select an option</option></select>
                              -->
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Show existing</label>
                            <select class="results-show-existing-only form-control">
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Sort by</label>
                            <select class="results-sorting form-control">
                              <option value="">Default</option>
                              <option value="distance">Distance</option>
                              <option value="rating">Rating</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Limit</label>
                            <select class="results-limit form-control">
                              <option>10</option>
                              <option>20</option>
                              <option>30</option>
                              <option>40</option>
                              <option>50</option>
                              <option>60</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Radius</label>
                            <select class="form-control places-radius-filter">
                              <option value="">No limit</option>
                              <option value="1609">1 miles</option>
                              <option value="8046">5 miles</option>
                              <option value="16093">10 miles</option>
                              <option value="24140">15 miles</option>
                              <option value="32186">20 miles</option>
                              <option value="48280">30 miles</option>
                              <option value="64373">40 miles</option>
                              <option value="96560">50 miles</option>
                            </select>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="listings-search-text-layout" role="tabpanel" aria-labelledby="listings-search-text-layout-tab">
                    <form id="placesSearchTextForm">
                      <div class="row" style="margin-top: 15px">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" class="form-control places-keyword-filter" placeholder="Barber Shops" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control places-location-filter" placeholder="Enter a city, state, zip code or an address." value="" />
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Show existing</label>
                            <select class="results-show-existing-only form-control">
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Limit</label>
                            <select class="results-limit form-control">
                              <option>10</option>
                              <option>20</option>
                              <option>30</option>
                              <option>40</option>
                              <option>50</option>
                              <option>60</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Radius</label>
                            <select class="form-control places-radius-filter">
                              <option value="">No limit</option>
                              <option value="1609">1 miles</option>
                              <option value="8046">5 miles</option>
                              <option value="16093">10 miles</option>
                              <option value="24140">15 miles</option>
                              <option value="32186">20 miles</option>
                              <option value="48280">30 miles</option>
                              <option value="64373">40 miles</option>
                              <option value="96560">50 miles</option>
                            </select>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="listings-search-outscraper-layout" role="tabpanel" aria-labelledby="listings-search-outscraper-layout-tab">
                    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-simple-bottom" id="outscraperSearchContentTabs" role="tablist">
                      <li class="nav-item active">
                        <a class="nav-link active" id="outscraper-search-finder-layout-tab" data-toggle="tab" href="#outscraper-search-finder-layout" role="tab" aria-expanded="true">
                          <b>Business Finder</b>
                        </a>
                      </li>
                      <li clas="nav-item">
                        <a class="nav-link" id="outscraper-search-category-layout-tab" data-toggle="tab" href="#outscraper-search-category-layout" role="tab">
                          <b>Category Loader</b>
                        </a>
                      </li>
                      <div class="clearfix"></div>
                    </ul>
                    <div class="tab-content" id="outscraperSearchTabsContent" style="margin-bottom: 10px">
                      <div class="tab-pane fade in active" id="outscraper-search-finder-layout" role="tabpanel" aria-labelledby="outscraper-search-finder-layout-tab">
                        <div class="info info-danger">
                          <p> Warning: Using this search method will immediately use your monthly scraping credits as data is immediately pulled. </p>
                        </div>
                        <form id="placesSearchOutscraperForm">
                          <div class="row" style="margin-top: 15px">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Keywords</label>
                                <input type="text" class="form-control places-keyword-filter" placeholder="Barber Shops" />
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Location</label>
                                <input type="text" class="form-control places-location-filter" placeholder="Enter a city, state, zip code or an address." value="" />
                              </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Show existing</label>
                                <select class="results-show-existing-only form-control">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Limit</label>
                                <select class="results-limit form-control">
                                  <option>10</option>
                                  <option>20</option>
                                  <option>50</option>
                                  <option>80</option>
                                  <option>100</option>
                                  <option>150</option>
                                  <option>200</option>
                                  <option>250</option>
                                  <option>300</option>
                                  <option>350</option>
                                  <option>400</option>
                                  <option>450</option>
                                  <option>500</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Auto Create Listings</label>
                                <select class="auto-create-listings form-control">
                                  <option>No</option>
                                  <option>Yes</option>
                                </select>
                                <small>Create the listings as soon as they are loaded.</small>
                              </div>
                              <div class="form-group pictures-reviews-config hidden">
                                <label>Auto load pictures/reviews</label>
                                <select class="results-limit form-control">
                                  <option>No</option>
                                  <option>Yes</option>
                                </select>
                                <small>Load the pictures and reviews for the new listings.</small>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade" id="outscraper-search-category-layout" role="tabpanel" aria-labelledby="outscraper-search-category-layout-tab">
                        <div class="info info-danger">
                          <p> Warning: Using this search method will immediately use your monthly scraping credits as data is immediately pulled. </p>
                          <p>
                            <b>Warning: This feature is experimental. Please do not use it until this notice has been removed.</b>
                          </p>
                        </div>
                        <form id="categorySearchOutscraperForm">
                          <div class="row outscraper-category-loader category-fields" style="margin-top: 15px">
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="country form-control" placeholder="Type a country" value="" />
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>State</label>
                                <input type="text" class="state form-control" placeholder="Enter a state" />
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>City</label>
                                <input type="text" class="city form-control" placeholder="Enter a city" />
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>Zip Code</label>
                                <input type="text" class="zip form-control" placeholder="Enter a zip code" />
                              </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Category:</label>
                                <select class="select5646" tabindex="-1">
                                  <option value="">Select an option</option>
                                  <option value="485">3D Manufacturers</option>
                                  <option value="392"> 55 Gallon Drum Manufacturers and Suppliers </option>
                                  <option value="312">Water Jet Cutting</option>
                                  <option value="355">Wire Forms Manufacturers</option>
                                  <option value="411">Wire Mesh Manufacturers</option>
                                  <option value="419">Wire Rope Manufacturers</option>
                                  <option value="361">Work Benches Manufacturers</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Sub categories:</label>
                                <select class="sub-category select2 select2-offscreen" multiple="multiple" tabindex="-1"></select>
                                <label class="pull-right">
                                  <input type="checkbox" class="mark-all-select2" /> Mark all </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Sub sub categories:</label>
                                <select class="sub-sub-category select2 select2-offscreen" multiple="multiple" tabindex="-1"></select>
                                <label class="pull-right">
                                  <input type="checkbox" class="mark-all-select2" /> Mark all </label>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Auto Create Listings</label>
                                <select class="auto-create-listings form-control">
                                  <option>No</option>
                                  <option>Yes</option>
                                </select>
                                <small>Create the listings as soon as they are loaded.</small>
                              </div>
                              <div class="form-group pictures-reviews-config hidden">
                                <label>Auto load pictures/reviews</label>
                                <select class="results-limit form-control">
                                  <option>No</option>
                                  <option>Yes</option>
                                </select>
                                <small>Load the pictures and reviews for the new listings.</small>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Category Depth</label>
                                <select class="category-depth form-control">
                                  <optgroup label="Category levels"> All <option>Top Only</option>
                                    <option>Top + Sub</option>
                                    <option>Sub Only</option>
                                    <option>Sub + Sub-sub</option>
                                    <option>Sub-sub Only</option>
                                  </optgroup>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Limit</label>
                                <select class="results-limit form-control">
                                  <option>10</option>
                                  <option>20</option>
                                  <option>50</option>
                                  <option>80</option>
                                  <option>100</option>
                                  <option>150</option>
                                  <option>200</option>
                                  <option>250</option>
                                  <option>300</option>
                                  <option>350</option>
                                  <option>400</option>
                                  <option>450</option>
                                  <option>500</option>
                                </select>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-topbar">
                  <div class="col-md-12">
                    <div class="category-topbar category-fields">
                      <h4>Pre-select categories:</h4>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Category:</label>
                            <select class="category select2 " tabindex="-1">
                              <option value="">Select an option</option>
                              <option value="485">3D Manufacturers</option>
                              <option value="392">55 Gallon Drum Manufacturers and Suppliers</option>
                              <option value="440">Adhesive Manufacturers</option>
                              <option value="338">AGV Manufacturers</option>
                              <option value="372">Air Compressors Manufacturers</option>
                              <option value="450">Air Conditioner Manufacturers</option>
                              <option value="320">Air Cylinders Manufacturers</option>
                              <option value="394">Air Filter Manufacturers</option>
                              <option value="473">Air Pollution Control Manufacturers</option>
                              <option value="471">Alloys Manufacturers</option>
                              <option value="297">Aluminum Manufacturers</option>
                              <option value="375">Automation Equipment Manufacturers</option>
                              <option value="475">Baler Manufacturers</option>
                              <option value="380">Ball Bearings Manufacturers</option>
                              <option value="438">Ball Screws Manufacturers</option>
                              <option value="451">Ball Valve Manufacturers &amp; Suppliers</option>
                              <option value="492">Battery Manufacturers</option>
                              <option value="348">Blow Molding Manufacturers</option>
                              <option value="427">Blower Manufacturers</option>
                              <option value="488">Boat Manufacturers</option>
                              <option value="388">Boilers Manufacturers</option>
                              <option value="373">Bolt Manufacturers</option>
                              <option value="474">Box Manufacturers</option>
                              <option value="412">Broaching Manufacturers</option>
                              <option value="437">Brush Manufacturers</option>
                              <option value="468">Butterfly Valve Manufacturers</option>
                              <option value="326">Calibration Services Manufacturers</option>
                              <option value="313">Car Wash Equipment Manufacturers</option>
                              <option value="421">Cardboard Tube Manufacturers</option>
                              <option value="289">Carrying Case Manufacturers</option>
                              <option value="435">Caster Manufacturers</option>
                              <option value="288">Centrifugal Pumps Manufacturers</option>
                              <option value="334">Centrifuges Manufacturers</option>
                              <option value="299">Ceramic Machining</option>
                              <option value="358">Check Valves Manufacturers</option>
                              <option value="495">Chemical Manufacturers</option>
                              <option value="298">Chiller Manufacturers</option>
                              <option value="416">Clean Room Manufacturers</option>
                              <option value="301">CNC Machining Manufacturers</option>
                              <option value="291">Coating Services Manufacturers</option>
                              <option value="354">Cold Forming Manufacturers</option>
                              <option value="321">Contract Manufacturing</option>
                              <option value="398">Contract Packaging</option>
                              <option value="406">Conveyor Belts</option>
                              <option value="347">Conveyor Manufacturers</option>
                              <option value="464">Cooling Tower Manufacturers</option>
                              <option value="310">Copper Manufacturers</option>
                              <option value="360">Crane Manufacturers</option>
                              <option value="476">Dairy Manufacturers</option>
                              <option value="343">Data Acquisition Systems</option>
                              <option value="446">Deburring Equipment Manufacturers</option>
                              <option value="444">Diaphragm Valves</option>
                              <option value="415">Die Castings Manufacturers</option>
                              <option value="300">Die Cutting Manufacturers</option>
                              <option value="302">Dip Molding Manufacturers</option>
                              <option value="448">Dryers Manufacturers</option>
                              <option value="410">Dust Collector Manufacturers</option>
                              <option value="456">Dynamometer Manufacturers</option>
                              <option value="366">EDM Manufacturers</option>
                              <option value="393">Electric Coil Manufacturers and Suppliers</option>
                              <option value="420">Electric Heaters Manufacturers</option>
                              <option value="452">Electric Hoist Manufacturers &amp; Suppliers</option>
                              <option value="424">Electric Motor Manufacturers</option>
                              <option value="431">Electric Transformer Manufacturers</option>
                              <option value="445">Electroless Nickel Plating Manufacturers</option>
                              <option value="339">Electronic Connectors Manufacturers</option>
                              <option value="466">Electronic Enclosure Manufacturers</option>
                              <option value="496">Elevator Manufacturers</option>
                              <option value="371">EMI Shielding Manufacturers</option>
                              <option value="434">Environmental Chamber Manufacturers</option>
                              <option value="333">Fasteners Manufacturers</option>
                              <option value="432">Fiberglass Fabrication Manufacturers</option>
                              <option value="329">Filtration Systems Manufacturers</option>
                              <option value="436">Flexible Shaft Couplings Manufacturers</option>
                              <option value="426">Flow Meter Manufacturers</option>
                              <option value="350">Foam Fabricating and Converting</option>
                              <option value="306">Forgings Manufacturers</option>
                              <option value="389">Forklifts Manufacturers</option>
                              <option value="400">Friction Materials</option>
                              <option value="367">Furnace Manufacturers</option>
                              <option value="480">Furniture Manufacturers</option>
                              <option value="472">Gas Springs Manufacturers</option>
                              <option value="433">Gasket Manufacturers</option>
                              <option value="292">Gear Manufacturers</option>
                              <option value="494">Generator Manufacturers</option>
                              <option value="319">Glass Cutting Manufacturers</option>
                              <option value="483">Glass Manufacturers</option>
                              <option value="447">Graphite Machining Manufacturers</option>
                              <option value="386">Gratings Manufacturers</option>
                              <option value="395">Grey Iron Castings</option>
                              <option value="332">Heat Exchanger Manufacturers</option>
                              <option value="378">Heat Treating</option>
                              <option value="442">Heating Element Manufacturers</option>
                              <option value="402">Hinge Manufacturers and Suppliers</option>
                              <option value="430">Hose Reel Manufacturers</option>
                              <option value="493">HVAC Manufacturers</option>
                              <option value="351">Hydraulic Cylinders Manufacturers</option>
                              <option value="374">Hydraulic Lift Manufacturers</option>
                              <option value="462">Hydraulic Motor Manufacturers</option>
                              <option value="467">Hydraulic Pump Manufacturers</option>
                              <option value="391">Hydraulic Valves Manufacturers &amp; Suppliers</option>
                              <option value="453">Industrial Laser Manufacturers</option>
                              <option value="470">Industrial Ovens Manufacturers</option>
                              <option value="465">Infrared Heater Manufacturers</option>
                              <option value="308">Injection Molded Plastic Manufacturers</option>
                              <option value="401">Investment Casting Manufacturers</option>
                              <option value="359">Labeling Machinery Manufacturers</option>
                              <option value="383">Laser Cutting Manufacturers</option>
                              <option value="296">Lasers Manufacturers</option>
                              <option value="363">Leak Detector Manufacturers</option>
                              <option value="370">Linear Actuator Manufacturers</option>
                              <option value="423">Linear Bearings Manufacturers</option>
                              <option value="337">Linear Slides Manufacturers</option>
                              <option value="418">Liquid Filter Manufacturers</option>
                              <option value="439">Load Cell Manufacturers</option>
                              <option value="413">Lock Manufacturers</option>
                              <option value="304">Lubricants Manufacturers</option>
                              <option value="341">Lubrication Systems Manufacturers</option>
                              <option value="356">Machine Guards</option>
                              <option value="344">Machine Vision</option>
                              <option value="384">Machinery Rebuilders</option>
                              <option value="404">Magnet Manufacturers and Suppliers</option>
                              <option value="294">Marking Machinery Manufacturers</option>
                              <option value="352">Metal Etching Manufacturers</option>
                              <option value="403">Metal Fabrication Manufacturers</option>
                              <option value="353">Metal Spinning Manufacturers</option>
                              <option value="303">Metal Stamping Manufacturers</option>
                              <option value="368">Metering Pump Manufacturers</option>
                              <option value="409">Mezzanine Manufacturers &amp; Suppliers</option>
                              <option value="330">Mixers Manufacturers</option>
                              <option value="315">Modular Buildings</option>
                              <option value="461">Name Plate Manufacturers</option>
                              <option value="455">Nickel Manufacturers</option>
                              <option value="317">Packaging Equipment Manufacturers</option>
                              <option value="307">Paint Finishing Equipment</option>
                              <option value="478">Pallet Inverter Manufacturers</option>
                              <option value="345">Pallet Manufacturers</option>
                              <option value="387">Parts Washer Manufacturer</option>
                              <option value="425">Perforated Metal Manufacturers</option>
                              <option value="481">Pharmaceutical Manufacturers</option>
                              <option value="290">Plastic Bag Manufacturers</option>
                              <option value="342">Plastic Container Manufacturers</option>
                              <option value="349">Plastic Extrusions Manufacturers</option>
                              <option value="336">Plastic Fabrication Manufacturers</option>
                              <option value="399">Plastic Tank Manufacturers and Suppliers</option>
                              <option value="295">Plastic Tubing Manufacturers</option>
                              <option value="382">Polyurethane Molding</option>
                              <option value="428">Powder Metal Parts Manufacturers</option>
                              <option value="381">Power Cord Manufacturers</option>
                              <option value="309">Power Supplies</option>
                              <option value="408">Pressure Gauges</option>
                              <option value="293">Pressure Transducers Manufacturers</option>
                              <option value="318">Pressure Vessels Manufacturers</option>
                              <option value="491">Private Label</option>
                              <option value="405">Pulverizer Manufacturers &amp; Suppliers</option>
                              <option value="322">Quick Release Couplings</option>
                              <option value="305">Roll Forming Manufacturers</option>
                              <option value="311">Ropes Manufacturers</option>
                              <option value="390">Rotomolding Manufacturers</option>
                              <option value="417">Rubber Extrusion Manufacturers</option>
                              <option value="335">Rubber Molding Manufacturers</option>
                              <option value="340">Rubber Roller Manufacturers</option>
                              <option value="396">Rubber Tubing</option>
                              <option value="364">Sandblasting Equipment Manufacturers</option>
                              <option value="422">Scales</option>
                              <option value="397">Screw Machine Product Manufacturers</option>
                              <option value="484">Semiconductor Manufacturers</option>
                              <option value="385">Sewing Contractor</option>
                              <option value="469">Shaft Coupling Manufacturers</option>
                              <option value="441">Shredders</option>
                              <option value="365">Solenoid Valve Manufacturers</option>
                              <option value="376">Soundproofing</option>
                              <option value="328">Spring Manufacturers</option>
                              <option value="407">Static Eliminator</option>
                              <option value="314">Steel Manufacturers</option>
                              <option value="346">Steel Service Centers</option>
                              <option value="323">Steel Shelving</option>
                              <option value="454">Storage Rack Manufacturers &amp; Suppliers</option>
                              <option value="379">Tape Suppliers</option>
                              <option value="487">Thermal Insulation Manufacturers</option>
                              <option value="316">Thermocouple Manufacturers</option>
                              <option value="429">Titanium Manufacturers</option>
                              <option value="477">Trailer Manufacturers</option>
                              <option value="325">Transformers Manufacturers</option>
                              <option value="327">Tube Fabrication</option>
                              <option value="357">Tube Forming Machines</option>
                              <option value="463">Tungsten Manufacturers</option>
                              <option value="443">Ultrasonic Cleaners</option>
                              <option value="369">Vacuum Cleaners Manufacturers</option>
                              <option value="414">Vacuum Forming Manufacturers</option>
                              <option value="331">Vacuum Pumps Manufacturers</option>
                              <option value="324">Vibration Absorbers Manufacturers</option>
                              <option value="449">Vibratory Feeder Manufacturers</option>
                              <option value="312">Water Jet Cutting</option>
                              <option value="355">Wire Forms Manufacturers</option>
                              <option value="411">Wire Mesh Manufacturers</option>
                              <option value="419">Wire Rope Manufacturers</option>
                              <option value="361">Work Benches Manufacturers</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Sub categories:</label>
                            <select class="sub-category select2 select2-offscreen" multiple="multiple" tabindex="-1"></select>
                            <label class="pull-right">
                              <input type="checkbox" class="mark-all-select2"> Mark all </label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Sub sub categories:</label>
                            <select class="sub-sub-category select2 select2-offscreen" multiple="multiple" tabindex="-1"></select>
                            <label class="pull-right">
                              <input type="checkbox" class="mark-all-select2"> Mark all </label>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4 col-md-offset-4" style="margin-top: 10px;margin-bottom: 10px;">
                          <div class="form-group">
                            <button class="btn-block btn btn-success reload-places-data">
                              <b>Search Businesses</b>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div id="listingsLoaderDatatable_wrapper" class="dataTables_wrapper no-footer">
                  <table id="listingsLoaderDatatable" class="table table-striped dataTable no-footer" style="width: 100%" aria-describedby="listingsLoaderDatatable_info">
                    <thead>
                      <tr>
                        <th class="no-sort" colspan="2" rowspan="1">
                          <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0px">
                              <select class="form-control bulk-actions">
                                <option value="">Bulk Actions</option>
                                <option value="load-outscraper-data"> Load Missing Data </option>
                                <option value="create-listing-data"> Create Listings </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3" style="padding: 0">
                            <div class="form-group" style="margin-bottom: 0px">
                              <button class="disabled btn btn-block btn-success execute-bulk-actions" style="height: 34px"> Apply </button>
                            </div>
                          </div>
                        </th>
                        <th style="width: 5%" class="no-sort hidden" rowspan="1" colspan="1"></th>
                      </tr>
                      <tr>
                        <th class="no-sort sorting_disabled" style="width: 13px" rowspan="1" colspan="1" aria-label="">
                          <div class="row" style="display: flex; align-items: center">
                            <div class="col-md-12 text-center">
                              <input type="checkbox" class="mark-all-in-table" />
                            </div>
                          </div>
                        </th>
                        <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Information" style="width: 341px"> Information </th>
                        <th style="width: 50px" class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"> Actions </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="odd">
                        <td valign="top" colspan="3" class="dataTables_empty"> No data available in table </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="dataTables_info" id="listingsLoaderDatatable_info" role="status" aria-live="polite"> Showing 0 to 0 of 0 entries </div>
                  <div id="listingsLoaderDatatable_processing" class="dataTables_processing" style="display: none"> Processing... </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 nopad" style="position: sticky; top: 40px">
              <div id="mapView" style="position: relative;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.6630893237834!2d67.00740971551608!3d24.84119278406308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33dfc94ef2283%3A0xd29b8cc455121f77!2sUnited%20State%20of%20America%20Consulate%20General%20Karachi!5e0!3m2!1sen!2s!4v1671199490570!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="nav3" role="tabpanel" aria-labelledby="nav3-tab">
          <div class="col-md-6">
            <div class="form-group">
              <label>Google API Key</label>
              <input type="text" class="form-control" name="google_yelp_listings__google_api_key" id="google_yelp_listings__google_api_key" value="AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek">
              <small>You must have billing enabled in your account and the Google project should not have referrer restrictions.</small>
            </div>
            <div class="form-group">
              <label>Yelp API Key</label>
              <input type="text" class="form-control" name="google_yelp_listings__yelp_api_key" id="google_yelp_listings__yelp_api_key" value="PWiDKIIO_f6GJatTG6g2KV_F8c6nxaW3licQdwbkd14UFwGJqufUrTHSFBIgih_wzpB2Y9ps8prTahvWrIcCroPDTKJPLhB5BfQ_qbrtdXrE0JJ6OP47_hhb2aCLXnYx" style="padding-right: 80px;text-overflow: ellipsis;">
              <a class="" href="https://www.yelp.com/signup" target="_blank"></a>
              <small>You can find a guide about setting up a Yelp Fusion API key <a href="https://help.directorytoolkit.com/faqs/how-do-you-get-and-use-a-yelp-api-key" target="_blank">here</a>. </small>
            </div>
            <div class="form-group">
              <label>Outscraper API Key</label>
              <input type="text" class="form-control" name="google_yelp_listings__outscraper_api_key" id="google_yelp_listings__outscraper_api_key" value="Z29vZ2xlLW9hdXRoMnwxMDQ0NzQwNzkzMjMxNTM2MDIyODB8ZjlmNWM1OTIzMw" style="padding-right: 80px;text-overflow: ellipsis;">
              <a class="" href="https://www.dir.link/import-API" target="_blank"></a>
              <small>You can find a guide about setting up an Outscraper API key <a href="https://help.directorytoolkit.com/faqs/how-do-you-get-and-use-an-outscraper-api-key" target="_blank">here</a>. </small>
            </div>
            <div class="form-group">
              <label>Default membership level</label>
              <select class="form-control" name="google_yelp_listings__default_membership_level" id="google_yelp_listings__default_membership_level">
                <option value="">Select an option</option>
                <option value="Classified Ads Pro">Classified Ads Pro</option>
                <option value="MNM Managed Reporting">MNM Managed Reporting</option>
                <option selected="" value="Free/Claim">Free/Claim</option>
                <option value="General User Account">General User Account</option>
                <option value="Admin - Blog Author">Admin - Blog Author</option>
                <option value="Basic Listing">Basic Listing</option>
                <option value="Featured Listing">Featured Listing</option>
                <option value="Premium Listing">Premium Listing</option>
              </select>
              <small>Select the default membership level to use for the new listings created with the generator.</small>
            </div>
            <a class="btn pull-right btn-cta btn-success" id="get_Update">Submit</a>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <span data-mapi-embed="khRcjmLafv">Get additional help</span>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="google_yelp_listings__enable_photos" id="google_yelp_listings__enable_photos" checked=""> Enable listing photo galleries. </label>
            </div>
            <div class="form-group">
              <label>Default photo count</label>
              <input class="form-control" type="text" name="google_yelp_listings__photos_limit" id="google_yelp_listings__photos_limit" value="20">
            </div>
            <div class="form-group">
              <label>Profile picture image width</label>
              <input class="form-control" type="text" name="google_yelp_listings__profile_picture_width" id="google_yelp_listings__profile_picture_width" value="0">
              <small>Enter the width by which the profile pictures should be restricted to. Cropping will happen based on the center of the picture. Leave at 0 to load the full size.</small>
            </div>
            <div class="form-group">
              <label>Profile picture image height</label>
              <input class="form-control" type="text" name="google_yelp_listings__profile_picture_height" id="google_yelp_listings__profile_picture_height" value="0">
              <small>Enter the height by which the profile pictures should be restricted to. Cropping will happen based on the center of the picture. Leave at 0 to load the full size.</small>
            </div>
            <div class="form-group">
              <label>Gallery picture image width</label>
              <input class="form-control" type="text" name="google_yelp_listings__gallery_picture_width" id="google_yelp_listings__gallery_picture_width" value="0">
              <small>Enter the width by which the gallery pictures should be restricted to. Cropping will happen based on the center of the picture. Leave at 0 to load the full size.</small>
            </div>
            <div class="form-group">
              <label>Gallery picture image height</label>
              <input class="form-control" type="text" name="google_yelp_listings__gallery_picture_height" id="google_yelp_listings__gallery_picture_height" value="0">
              <small>Enter the height by which the gallery pictures should be restricted to. Cropping will happen based on the center of the picture. Leave at 0 to load the full size.</small>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="google_yelp_listings__enable_big_images" id="google_yelp_listings__enable_big_images" checked=""> Load big images. </label>
              <small>Check this box to use big images for the listing gallery pictures. Recommended for when you have a set height/width. Other-wise just leave it unchecked to load the reduced image automatically.</small>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="google_yelp_listings__enable_reviews" id="google_yelp_listings__enable_reviews" checked=""> Enable listing reviews. </label>
            </div>
            <div class="form-group">
              <label>Default review count</label>
              <input class="form-control" type="text" name="google_yelp_listings__reviews_limit" id="google_yelp_listings__reviews_limit" value="100">
            </div>
            <div class="form-group">
              <label>Default gallery name</label>
              <input placeholder="%company% Social Pictures" name="google_yelp_listings__default_gallery_name" type="text" class="form-control" id="google_yelp_listings__default_gallery_name" value="%company% Images">
              <small>Enter the name that new galleries created from Google/Yelp images should have. <br>You can use the following replacement patterns within the value: <ul>
                  <li>%company%: The company name.</li>
                </ul>Example: %company% Social Pictures </small>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="google_yelp_listings__verified_status" id="google_yelp_listings__enable_reviews"> Verify listings by default. </label>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="google_yelp_listings__verified_status" id="google_yelp_listings__enable_reviews" checked=""> Load contact information by default with each scrape. </label>
            </div>
            <div class="form-group">
              <label>Default search location</label>
              <input type="text" name="google_yelp_listings__default_search_location" class="form-control" id="google_yelp_listings__default_search_location" value="">
              <small>Enter the default search location to use on the listings generator.</small>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> 
<script>
  $(document).ready(function() {
    $('#listingsDatatable').DataTable();
  });
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
<?php
add_action('admin_footer', 'my_ajax_without_file');
function my_ajax_without_file()
{ ?>
  <script>
    $(document).on("click","#get_Update",function() {
      ajaxurl = '<?php echo Plugins_Url('/directory_plugin-master/admin_template/directory_configuration_get_update.php') ?>';
      var GoogleApi = $("#google_yelp_listings__google_api_key").val();
      var YelpAPIKey = $("#google_yelp_listings__yelp_api_key").val();  
      var OutscraperAPIKey = $("#google_yelp_listings__outscraper_api_key").val();
      var Defaultmembershiplevel = $("#google_yelp_listings__default_membership_level").val();
      var data = {
        'GoogleApi': GoogleApi,
        'YelpAPIKey': YelpAPIKey,
        'OutscraperAPIKey': OutscraperAPIKey,
        'Defaultmembershiplevel': Defaultmembershiplevel
      };
      
      
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function(response) {
          //console.log(data);                
        }

      });

    });
  </script>
<?php
}

add_action("wp_ajax_frontend_action_without_file", "frontend_action_without_file");
add_action("wp_ajax_nopriv_frontend_action_without_file", "frontend_action_without_file");

function frontend_action_without_file()
{
  echo json_encode($_POST);
  wp_die();
}

?>
