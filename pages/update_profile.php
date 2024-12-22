<?php
include '../header.php';
include '../connections.php';

// Check if the teacher is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing teacher details
$query = $conn->prepare("SELECT * FROM teachers WHERE user_id = ?");
if (!$query) {
    die("Failed to prepare query: " . $conn->error);
}

$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$teacher = $result ? $result->fetch_assoc() : [];

// Set default values to avoid warnings
$teacher = $teacher ?? [
    'subject' => '',
    'language' => '',
    'youtube_video_url' => '',
    'level' => '',
    'phone' => '',
    'charge_per_hour' => '',
    'country_id' => '',
    'available_in_week' => '',
];

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h3>Teacher Profile
                    </h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" method="post" enctype="multipart/form-data"
                        action="update_profile_action.php">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"
                                value="<?= htmlspecialchars($teacher['subject'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="language" id="language" placeholder="Language"
                                value="<?= htmlspecialchars($teacher['language'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="youtube_video_url" id="youtube_video_url"
                                placeholder="YouTube Video URL"
                                value="<?= htmlspecialchars($teacher['youtube_video_url'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="level" id="level" placeholder="Level"
                                value="<?= htmlspecialchars($teacher['level'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone"
                                value="<?= htmlspecialchars($teacher['phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="file" class="form-control" name="photo" id="photo" placeholder="Profile photo">
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="charge_per_hour" id="charge_per_hour"
                                placeholder="Charge per Hour" step="0.01"
                                value="<?= htmlspecialchars($teacher['charge_per_hour'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" name="country_id" id="country_id">
                                <option value="">Select Country</option>
                                <?php
                                $countries_query = $conn->query("SELECT * FROM countries");
                                if (!$countries_query) {
                                    die("Failed to fetch countries: " . $conn->error);
                                }

                                while ($country = $countries_query->fetch_assoc()) {
                                    $selected = isset($teacher['country_id']) && $teacher['country_id'] == $country['id'] ? 'selected' : '';
                                    echo "<option value='{$country['id']}' $selected>{$country['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="available_in_week" id="available_in_week"
                                placeholder="Available Days (Comma-separated)"
                                value="<?= htmlspecialchars($teacher['available_in_week'] ?? '') ?>">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>