<?php


namespace App\traits;


trait ManageUtilities
{
    use ActiveDeleted;
    use CheckDuplicate;
    use ChangeViews;
    use DeleteRecord;
    use ResetPagination;
    use UploadDownload;
}
