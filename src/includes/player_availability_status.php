<!-- Badge Color for Availability Status -->
<div class="flex items-center justify-center">
    <?php if ($player['player_availability_status']  === 'Available') :?>
        <span class="<?= badgeSuccess()?> flex items-center justify-center">
            <?= ucfirst($player['player_availability_status']) ?>
        </span>
    <?php else: ?>
        <span class="<?= badgeDanger()?>  flex justify-center items-center">
            <?= ucfirst($player['player_availability_status']) ?>
        </span>
    <?php endif; ?>
</div>
