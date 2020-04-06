<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container">
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <?=$breadcrumbs?>
                </div>
            </div>
            <div class="row paddingBottom40">
                <div class="col-sm-12">
                    <h1><?=$lng->get('This is the list of last searches')?></h1>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?=$lng->get('No')?></th>
                                <th><?=$lng->get('Search')?></th>
                                <th><?=$lng->get('View')?></th>
                                <th><?=$lng->get('Action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searches as $search): ?>
                            <tr>
                                <td><?=$search['id']?></td>
                                <td><?=$search['query']?> <a target="_blank" href="search/<?=$search['id']?>/<?=urlencode($search['query'])?>"><?=$lng->get('show')?></a></td>
                                <td><?=$search['view']?></td>
                                <td><a href="user/add_answer/<?=$search['id']?>"><?=$lng->get('Answer')?></a></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>