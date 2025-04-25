<div class="row align-items-center">
    <div class="col-md-8">
        <div class="form-group">
            <label for="seoPageSelect">Select Page</label>
            <div class="input-group">
                <select class="form-control" id="seoPageSelect">
                    <?php
                    $seoSettings = json_decode((string) $admin->getSeoSettings(), true);
                    $existingPages = json_decode((string) $admin->getAllPageNames(), true);

                    foreach ($existingPages as $page):
                        $selected = ($page === $currentScript) ? 'selected' : '';
                    ?>
                        <option value="<?= $page ?>" <?= $selected ?>>
                            <?= ucfirst(str_replace(['.php', '-'], ['', ' '], $page)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-outline-info btn-sm" type="button" id="addPageBtn">
                <i class="fas fa-plus"></i> Add Page
            </button>
        </div>
    </div>
</div>
<form id="seoSettingsForm" class="mt-4">
    <input type="hidden" id="seoId" name="id">
    <input type="hidden" id="seoPageName" name="page_name">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="seoPageTitle">Page Title</label>
                <input type="text" class="form-control" id="seoPageTitle" name="page_title" required>
            </div>
            <div class="form-group">
                <label for="seoSubTitle">Sub Title</label>
                <input type="text" class="form-control" id="seoSubTitle" name="sub_title">
            </div>
            <div class="form-group">
                <label for="seoMetaDescription">Meta Description</label>
                <textarea class="form-control" id="seoMetaDescription" name="meta_description" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="seoMetaKeywords">Meta Keywords (comma separated)</label>
                <textarea class="form-control" id="seoMetaKeywords" name="meta_keywords" rows="1"></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="seoCanonicalUrl">Canonical URL</label>
                <input type="url" class="form-control" id="seoCanonicalUrl" name="canonical_url">
            </div>

            <div class="form-group">
                <label for="seoOgTitle">OpenGraph Title</label>
                <input type="text" class="form-control" id="seoOgTitle" name="og_title">
            </div>

            <div class="form-group">
                <label for="seoOgDescription">OpenGraph Description</label>
                <textarea class="form-control" id="seoOgDescription" name="og_description" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label for="seoOgImage">OpenGraph Image URL</label>
                <input type="url" class="form-control" id="seoOgImage" name="og_image">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-bold mb-0">Breadcrumbs</h6>
                <button type="button" id="addBreadcrumbBtn" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-plus"></i> Add Breadcrumb
                </button>
            </div>
            <div class="breadcrumbsContainer mt-3" id="breadcrumbsContainer">
                <!-- Breadcrumb fields will be added here -->
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 text-left">
            <button type="submit" class="btn bg-gradient-success">Save SEO Settings</button>
        </div>
    </div>
</form>