<?php if (isset($detail)): ?>
    <?php if ($detail instanceof \Model\Disc): ?>
        <?php echo $detail->name_zh ?> lv.<?php echo $detail->level ?>
    <?php elseif ($detail instanceof \Model\Weapon): ?>
        <?php if ($detail->special): ?>
            <?php if ($detail->abnormal): ?>
                <span class="btn btn-danger btn-xs">异常</span><br/>
            <?php endif; ?>
            EX&nbsp;<?php echo $detail->special; ?><br/>
        <?php endif; ?>
        <?php if ($detail->strengthen): ?>
            打磨&nbsp;<?php echo $detail->strengthen; ?><br/>
        <?php endif; ?>
        <?php if ($detail->st1): ?>
            <?php echo $detail->st1['name_zh']; ?>&nbsp;<?php echo $detail->st1['val']; ?><br/>
        <?php endif; ?>
        <?php if ($detail->st2): ?>
            <?php echo $detail->st2['name_zh']; ?>&nbsp;<?php echo $detail->st2['val']; ?><br/>
        <?php endif; ?>
        <?php if ($detail->st3): ?>
            <?php echo $detail->st3['name_zh']; ?>&nbsp;<?php echo $detail->st3['val']; ?><br/>
        <?php endif; ?>
    <?php elseif ($detail instanceof \Model\Mag): ?>
        防御 <?php echo $detail->defense[0]; ?>+<?php echo $detail->defense[1]; ?><br/>
        攻击 <?php echo $detail->power[0]; ?>+<?php echo $detail->power[1]; ?><br/>
        敏捷 <?php echo $detail->dex[0]; ?>+<?php echo $detail->dex[1]; ?><br/>
        精神 <?php echo $detail->mind[0]; ?>+<?php echo $detail->mind[1]; ?><br/>
        同步 <?php echo $detail->synchro; ?> 智商 <?php echo $detail->IQ; ?>
    <?php endif; ?>
<?php endif; ?>
