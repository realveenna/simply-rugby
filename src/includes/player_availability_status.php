<!-- Badge Color for Availability Status -->
<div class="w-fit">
    <?php if ($player['player_availability_status']  === 'Available') :?>
        <span class="<?= badgeSuccess()?>">
            <?= ucfirst($player['player_availability_status']) ?>
        </span>
    <?php else: ?>
        <span class="<?= badgeDanger()?>">
            <?= ucfirst($player['player_availability_status']) ?>
        </span>
    <?php endif; ?>
</div>
