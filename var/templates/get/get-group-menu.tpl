<br/>
                        <div>
                            <ul class="unorderedlisttree" id="checkchildren">
                            <li>
                                  <input type="checkbox" style ="display:none;" name="menuid_all[]" value="select_all"/>
                                  <label class="selectmenu" style="display:inline">All</label>
                                <ul>
                                 {foreach name=outer key=meun_id1 item=menu_level1 from=$level1}

                                  <li>
                                  <input type="checkbox" class="menuid" {if $menu_level1.selected!=-1 || $menu_level1.is_default==1}checked{/if} style ="display:none;" name="menuid[]" value="{$menu_level1.menu_id}"/>
                                  <label {if $menu_level1.admin_menu ||$menu_level1.is_default==1}disabled='true'{/if} class="selectmenu" style="display:inline;font-size:14px;{if $menu_level1.admin_menu||$menu_level1.is_default==1}color:#D8D8D8; {/if}">
                                  {$menu_level1.title}
                                        </label>

                                  {foreach key=meun_id2 item=menu_level2 from=$level2}

                                    {if $menu_level1.menu_id == $menu_level2.parent_id}
                                    <ul>
                                      <li>
                                        <input  type="checkbox" class="menuid" style ="display:none;"  {if $menu_level2.selected!=-1|| $menu_level2.is_default==1}checked{/if}   name="menuid[]" value="{$menu_level2.menu_id}"/>
                                        <label {if $menu_level2.admin_menu||$menu_level2.is_default==1}disabled='true'{/if} class="selectmenu" style="display:inline;{if $menu_level2.admin_menu||$menu_level2.is_default==1}color:#D8D8D8; {/if}">
                                        {$menu_level2.title}
                                        </label>

                                          {foreach key=meun_id3 item=menu_level3 from=$level3}
                                            {if $menu_level2.menu_id == $menu_level3.parent_id}
                                            <ul>
                                              <li>
                                                <input  type="checkbox" class="menuid" style ="display:none;"  {if $menu_level3.selected!=-1 || $menu_level3.is_default==1}checked{/if}  name="menuid[]" value="{$menu_level3.menu_id}"/>
                                                <label {if $menu_level3.admin_menu ||$menu_level3.is_default==1}disabled='true'{/if} class="selectmenu" style="display:inline;{if $menu_level3.admin_menu||$menu_level3.is_default==1}color:#D8D8D8; {/if}">
                                                  {$menu_level3.title}
                                                </label>
                                                {foreach key=meun_id4 item=menu_level4 from=$level4}
                                                    {if $menu_level3.menu_id == $menu_level4.parent_id}
                                                    <ul>
                                                      <li>
                                                        <input  type="checkbox" class="menuid" style ="display:none;"  {if $menu_level4.selected!=-1 || $menu_level4.is_default==1}checked{/if}  name="menuid[]" value="{$menu_level4.menu_id}"/>
                                                        <label {if $menu_level4.admin_menu||$menu_level4.is_default==1}disabled='true' {/if} class="selectmenu" style="display:inline;font-weight:normal;{if $menu_level4.admin_menu||$menu_level4.is_default==1}color:#D8D8D8; {/if}">
                                                        {$menu_level4.title}
                                                        </label>
                                                      </li>
                                                      </ul>
                                                      {/if}
                                                  {/foreach}
                                              </li>
                                              </ul>
                                              {/if}
                                          {/foreach}

                                                   {foreach key=meun_id4 item=menu_level4 from=$level4}
                                                    {if $menu_level2.menu_id == $menu_level4.parent_id}
                                                    <ul>
                                                      <li>
                                                        <input  type="checkbox" class="menuid" style ="display:none;"  {if $menu_level4.selected!=-1 || $menu_level4.is_default==1}checked{/if}  name="menuid[]" value="{$menu_level4.menu_id}"/>
                                                        <label {if $menu_level4.admin_menu||$menu_level4.is_default==1}disabled='true' {/if} class="selectmenu" style="display:inline;{if $menu_level4.admin_menu||$menu_level4.is_default==1}color:#D8D8D8; {/if}">
                                                            {$menu_level4.title}
                                                        </label>
                                                      </li>
                                                      </ul>
                                                      {/if}
                                                  {/foreach}
                                      </li>
                                      </ul>
                                      {/if}

                                  {/foreach}
                                  
                                      {foreach key=meun_id4 item=menu_level4 from=$level4}
                                        {if $menu_level1.menu_id == $menu_level4.parent_id}
                                        <ul>
                                          <li>
                                            <input  type="checkbox" class="menuid" style ="display:none;"  {if $menu_level4.selected!=-1 || $menu_level4.is_default==1}checked{/if}  name="menuid[]" value="{$menu_level4.menu_id}"/>
                                            <label {if $menu_level4.admin_menu||$menu_level4.is_default==1}disabled='true' {/if} class="selectmenu" style="display:inline;{if $menu_level4.admin_menu||$menu_level4.is_default==1}color:#D8D8D8; {/if}">
                                                {$menu_level4.title}
                                            </label>
                                          </li>
                                          </ul>
                                          {/if}
                                      {/foreach}

                                  </li>

                                {/foreach}
                            </ul>
                            </li>
                            </ul>
                        </div>



 {literal}
                <script  type="text/javascript">
                jQuery(document).ready(function(){
                        jQuery("#checkchildren").checkboxTree({
                                        collapsedarrow: "./Includes/assets/images/checkboxtree/img-arrow-collapsed.gif",
                                        expandedarrow: "./Includes/assets/images/checkboxtree/img-arrow-expanded.gif",
                                        blankarrow: "./Includes/assets/images/checkboxtree/img-arrow-blank.gif",
                                        checkchildren: true,
                                        collapsed:false
                        });

});
                </script>
{/literal}