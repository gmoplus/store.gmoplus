<!-- variations admin template -->

{if $mode == 'list'}
    <!-- Varyasyon grupları listesi -->
    <div id="grid">
        <div class="grid-header">
            <h2>{$lang.variation_groups}</h2>
            <div class="buttons">
                <a href="{$rlBaseC}action=add_group" class="button">{$lang.add_variation_group}</a>
            </div>
        </div>
        
        <div class="grid-content">
            {if $groups}
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{$lang.name}</th>
                            <th>{$lang.variation_type}</th>
                            <th>{$lang.values_count}</th>
                            <th>{$lang.status}</th>
                            <th>{$lang.actions}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$groups item='group'}
                            <tr>
                                <td>{$group.ID}</td>
                                <td>{$group.Name}</td>
                                <td>{$lang.variation_type_|cat:$group.Type}</td>
                                <td>{$group.values_count}</td>
                                <td>{$group.Status}</td>
                                <td>
                                    <a href="{$rlBaseC}action=values&group_id={$group.ID}" title="{$lang.manage_values}">
                                        <img src="{$rlTplBase}img/edit.png" alt="{$lang.manage_values}" />
                                    </a>
                                    <a href="{$rlBaseC}action=edit_group&id={$group.ID}" title="{$lang.edit}">
                                        <img src="{$rlTplBase}img/edit.png" alt="{$lang.edit}" />
                                    </a>
                                    <a href="{$rlBaseC}action=delete_group&id={$group.ID}" 
                                       onclick="return confirm('{$lang.notice_delete_confirm}')" title="{$lang.delete}">
                                        <img src="{$rlTplBase}img/delete.png" alt="{$lang.delete}" />
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {else}
                <div class="info">{$lang.no_items_found}</div>
            {/if}
        </div>
    </div>

{elseif $mode == 'add_group' || $mode == 'edit_group'}
    <!-- Varyasyon grubu ekleme/düzenleme -->
    <form method="post" action="{$rlBaseC}{if $mode == 'edit_group'}action=edit_group&id={$group.ID}{else}action=add_group{/if}">
        <table class="form">
            <tr>
                <td class="name">{$lang.name} <span class="red">*</span></td>
                <td class="field">
                    <input type="text" name="group[name]" value="{$group.Name}" maxlength="255" class="text-input" />
                </td>
            </tr>
            <tr>
                <td class="name">{$lang.variation_type}</td>
                <td class="field">
                    <select name="group[type]">
                        <option value="size" {if $group.Type == 'size'}selected{/if}>{$lang.variation_type_size}</option>
                        <option value="color" {if $group.Type == 'color'}selected{/if}>{$lang.variation_type_color}</option>
                        <option value="material" {if $group.Type == 'material'}selected{/if}>{$lang.variation_type_material}</option>
                        <option value="other" {if $group.Type == 'other'}selected{/if}>{$lang.variation_type_other}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="name">{$lang.position}</td>
                <td class="field">
                    <input type="text" name="group[position]" value="{$group.Position|default:0}" maxlength="3" class="text-input numeric" />
                </td>
            </tr>
            <tr>
                <td class="name">{$lang.status}</td>
                <td class="field">
                    <select name="group[status]">
                        <option value="active" {if $group.Status == 'active'}selected{/if}>{$lang.active}</option>
                        <option value="inactive" {if $group.Status == 'inactive'}selected{/if}>{$lang.inactive}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="field">
                    <input type="submit" name="submit" value="{if $mode == 'edit_group'}{$lang.edit}{else}{$lang.add}{/if}" class="button" />
                    <a href="{$rlBaseC}" class="button">{$lang.cancel}</a>
                </td>
            </tr>
        </table>
    </form>

{elseif $mode == 'values'}
    <!-- Varyasyon değerleri yönetimi -->
    <div id="grid">
        <div class="grid-header">
            <h2>{$group.Name} - {$lang.variation_values}</h2>
            <div class="buttons">
                <a href="{$rlBaseC}" class="button">{$lang.back}</a>
            </div>
        </div>
        
        <!-- Yeni değer ekleme formu -->
        <form method="post" action="{$rlBaseC}action=values&group_id={$group.ID}">
            <table class="form">
                <tr>
                    <td class="name">{$lang.name} <span class="red">*</span></td>
                    <td class="field">
                        <input type="text" name="value[name]" maxlength="255" class="text-input" />
                    </td>
                </tr>
                {if $group.Type == 'color'}
                <tr>
                    <td class="name">{$lang.color_code}</td>
                    <td class="field">
                        <input type="text" name="value[color_code]" maxlength="7" class="text-input" placeholder="#FFFFFF" />
                    </td>
                </tr>
                {/if}
                <tr>
                    <td class="name">{$lang.position}</td>
                    <td class="field">
                        <input type="text" name="value[position]" value="0" maxlength="3" class="text-input numeric" />
                    </td>
                </tr>
                <tr>
                    <td class="name">{$lang.status}</td>
                    <td class="field">
                        <select name="value[status]">
                            <option value="active">{$lang.active}</option>
                            <option value="inactive">{$lang.inactive}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="field">
                        <input type="submit" name="submit_value" value="{$lang.add_value}" class="button" />
                    </td>
                </tr>
            </table>
        </form>
        
        <!-- Mevcut değerler listesi -->
        <div class="grid-content">
            {if $values}
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{$lang.name}</th>
                            {if $group.Type == 'color'}<th>{$lang.color_code}</th>{/if}
                            <th>{$lang.position}</th>
                            <th>{$lang.status}</th>
                            <th>{$lang.actions}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$values item='value'}
                            <tr>
                                <td>{$value.ID}</td>
                                <td>{$value.Name}</td>
                                {if $group.Type == 'color'}<td style="background-color: {$value.Color_code}">{$value.Color_code}</td>{/if}
                                <td>{$value.Position}</td>
                                <td>{$value.Status}</td>
                                <td>
                                    <a href="{$rlBaseC}action=delete_value&id={$value.ID}&group_id={$group.ID}" 
                                       onclick="return confirm('{$lang.notice_delete_confirm}')" title="{$lang.delete}">
                                        <img src="{$rlTplBase}img/delete.png" alt="{$lang.delete}" />
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {else}
                <div class="info">{$lang.no_items_found}</div>
            {/if}
        </div>
    </div>
{/if} 