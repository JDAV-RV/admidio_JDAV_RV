{if strlen($infoAlert) > 0}
    <div class="alert alert-info" role="alert"><i class="bi bi-info-circle-fill"></i>{$infoAlert}</div>
{/if}

<div class="table-responsive">
    <table id="forum-table" class="table table-hover" width="100%" style="width: 100%;">
        <thead>
            <tr>
                <th>{$l10n->get('SYS_TOPIC')}</th>
                {if $showCategories}
                    <th>{$l10n->get('SYS_CATEGORY')}</th>
                {/if}
                <th class="text-center">{$l10n->get('SYS_POSTS')}</th>
                <th class="text-center">{$l10n->get('SYS_VIEWS')}</th>
                <th>{$l10n->get('SYS_LAST_POST')}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {foreach $list as $row}
                <tr id="adm_topic_{$row.uuid}">
                    <td class="row align-items-center">
                        <div class="col">
                            <span class="fs-5"><a href="{$row.url}">{$row.title}</a></span>
                            <span class="d-block" style="font-size: 0.75rem">{$l10n->get('SYS_CREATED_BY_AND_AT', array($row.userNameWithLink, $row.timestamp))}</span>
                        </div>
                        <div class="col-auto"><img class="rounded-circle" style="max-height: 40px; max-width: 40px;" src="{$row.userProfilePhotoUrl}" /></div>
                    </td>
                    {if $showCategories}
                        <td>{$row.category}</td>
                    {/if}
                    <td class="text-center">{$row.repliesCount}</td>
                    <td class="text-center">{$row.views}</td>
                    <td>
                        {if $row.repliesCount > 0}
                            {$row.lastReplyInfo}
                        {/if}
                    </td>
                    <td class="text-end">
                        {include 'sys-template-parts/list.functions.tpl' data=$row}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
