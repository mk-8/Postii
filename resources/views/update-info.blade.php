<x-layout>

    <style>
        .image-wrapper {
            position: relative;
            cursor: pointer;
            display: inline-block;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .image-overlay::before {
            content: "×";
            color: #fff;
            font-size: 36px;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .image-wrapper:hover .image-overlay {
            opacity: 1;
        }

    </style>

    <div class="container container--narrow py-md-5">
        <a href="javascript:location.href= '/profile/{{$userData->username}}'" class="btn btn-dark btn-sm">
			<i class="fas fa-arrow-left"></i> Back
		</a>
        <h2 class="text-center mb-3">Update Your Information</h2>
        <form action="/update-info" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="avatar" class="form-label">Avatar:</label>
                <input type="file" class="form-control p-1" id="avatar" name="avatar" @if(!$userData->avatar) required @endif onchange="displayImage(this)">
                @if($userData->avatar)
                <div id="image-preview" class="position-relative mt-2">
                    <div class="image-wrapper rounded-circle" style="width: 100px; height: 100px; overflow: hidden;" onclick="removeImage()">
                        <img src="{{$userData->avatar }}" alt="Uploaded Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="image-overlay rounded-circle d-flex justify-content-center align-items-center">
                            <i class="bi bi-x text-danger"></i>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth:</label>
                <input type="date" class="form-control" id="dob" name="dob" value="{{ $userData->DOB ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="profession" class="form-label">Profession:</label>
                <input type="text" class="form-control" id="profession" name="profession" value="{{ $userData->Profession ?? '' }}"required>
            </div>
            <div class="mb-3">
                <label for="university" class="form-label">University/School:</label>
                <input type="text" class="form-control" id="university" name="university" value="{{ $userData->University ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country:</label>
                 <select class="form-control" id="country" name="country" required>
                    <option value="">Select your country</option>
                    <option value="Afghanistan" {{ $userData->Country == 'Afghanistan' ? 'selected' : '' }}>Afghanistan</option>
                    <option value="Albania" {{ $userData->Country == 'Albania' ? 'selected' : '' }}>Albania</option>
                    <option value="Algeria" {{ $userData->Country == 'Algeria' ? 'selected' : '' }}>Algeria</option>
                    <option value="American Samoa" {{ $userData->Country == 'American Samoa' ? 'selected' : '' }}>American Samoa</option>
                    <option value="Andorra" {{ $userData->Country == 'Andorra' ? 'selected' : '' }}>Andorra</option>
                    <option value="Angola" {{ $userData->Country == 'Angola' ? 'selected' : '' }}>Angola</option>
                    <option value="Anguilla" {{ $userData->Country == 'Anguilla' ? 'selected' : '' }}>Anguilla</option>
                    <option value="Antarctica" {{ $userData->Country == 'Antarctica' ? 'selected' : '' }}>Antarctica</option>
                    <option value="Antigua and Barbuda" {{ $userData->Country == 'Antigua and Barbuda' ? 'selected' : '' }}>Antigua and Barbuda</option>
                    <option value="Argentina" {{ $userData->Country == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                    <option value="Armenia" {{ $userData->Country == 'Armenia' ? 'selected' : '' }}>Armenia</option>
                    <option value="Aruba" {{ $userData->Country == 'Aruba' ? 'selected' : '' }}>Aruba</option>
                    <option value="Australia" {{ $userData->Country == 'Australia' ? 'selected' : '' }}>Australia</option>
                    <option value="Austria" {{ $userData->Country == 'Austria' ? 'selected' : '' }}>Austria</option>
                    <option value="Azerbaijan" {{ $userData->Country == 'Azerbaijan' ? 'selected' : '' }}>Azerbaijan</option>
                    <option value="Bahamas" {{ $userData->Country == 'Bahamas' ? 'selected' : '' }}>Bahamas</option>
                    <option value="Bahrain" {{ $userData->Country == 'Bahrain' ? 'selected' : '' }}>Bahrain</option>
                    <option value="Bangladesh" {{ $userData->Country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                    <option value="Barbados" {{ $userData->Country == 'Barbados' ? 'selected' : '' }}>Barbados</option>
                    <option value="Belarus" {{ $userData->Country == 'Belarus' ? 'selected' : '' }}>Belarus</option>
                    <option value="Belgium" {{ $userData->Country == 'Belgium' ? 'selected' : '' }}>Belgium</option>
                    <option value="Belize" {{ $userData->Country == 'Belize' ? 'selected' : '' }}>Belize</option>
                    <option value="Benin" {{ $userData->Country == 'Benin' ? 'selected' : '' }}>Benin</option>
                    <option value="Bermuda" {{ $userData->Country == 'Bermuda' ? 'selected' : '' }}>Bermuda</option>
                    <option value="Bhutan" {{ $userData->Country == 'Bhutan' ? 'selected' : '' }}>Bhutan</option>
                    <option value="Bolivia" {{ $userData->Country == 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
                    <option value="Bonaire, Sint Eustatius and Saba" {{ $userData->Country == 'Bonaire, Sint Eustatius and Saba' ? 'selected' : '' }}>Bonaire, Sint Eustatius and Saba</option>
                    <option value="Bosnia and Herzegovina" {{ $userData->Country == 'Bosnia and Herzegovina' ? 'selected' : '' }}>Bosnia and Herzegovina</option>
                    <option value="Botswana" {{ $userData->Country == 'Botswana' ? 'selected' : '' }}>Botswana</option>
                    <option value="Bouvet Island" {{ $userData->Country == 'Bouvet Island' ? 'selected' : '' }}>Bouvet Island</option>
                    <option value="Brazil" {{ $userData->Country == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                    <option value="British Indian Ocean Territory" {{ $userData->Country == 'British Indian Ocean Territory' ? 'selected' : '' }}>British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam" {{ $userData->Country == 'Brunei Darussalam' ? 'selected' : '' }}>Brunei Darussalam</option>
                    <option value="Bulgaria" {{ $userData->Country == 'Bulgaria' ? 'selected' : '' }}>Bulgaria</option>
                    <option value="Burkina Faso" {{ $userData->Country == 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                    <option value="Burundi" {{ $userData->Country == 'Burundi' ? 'selected' : '' }}>Burundi</option>
                    <option value="Cambodia" {{ $userData->Country == 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                    <option value="Cameroon" {{ $userData->Country == 'Cameroon' ? 'selected' : '' }}>Cameroon</option>
                    <option value="Canada" {{ $userData->Country == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="Cape Verde" {{ $userData->Country == 'Cape Verde' ? 'selected' : '' }}>Cape Verde</option>
                    <option value="Cayman Islands" {{ $userData->Country == 'Cayman Islands' ? 'selected' : '' }}>Cayman Islands</option>
                    <option value="Central African Republic" {{ $userData->Country == 'Central African Republic' ? 'selected' : '' }}>Central African Republic</option>
                    <option value="Chad" {{ $userData->Country == 'Chad' ? 'selected' : '' }}>Chad</option>
                    <option value="Chile" {{ $userData->Country == 'Chile' ? 'selected' : '' }}>Chile</option>
                    <option value="China" {{ $userData->Country == 'China' ? 'selected' : '' }}>China</option>
                    <option value="Christmas Island" {{ $userData->Country == 'Christmas Island' ? 'selected' : '' }}>Christmas Island</option>
                    <option value="Cocos (Keeling) Islands" {{ $userData->Country == 'Cocos (Keeling) Islands' ? 'selected' : '' }}>Cocos (Keeling) Islands</option>
                    <option value="Colombia" {{ $userData->Country == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                    <option value="Comoros" {{ $userData->Country == 'Comoros' ? 'selected' : '' }}>Comoros</option>
                    <option value="Congo" {{ $userData->Country == 'Congo' ? 'selected' : '' }}>Congo</option>
                    <option value="Congo, The Democratic Republic of the" {{ $userData->Country == 'Congo, The Democratic Republic of the' ? 'selected' : '' }}>Congo, The Democratic Republic of the</option>
                    <option value="Cook Islands" {{ $userData->Country == 'Cook Islands' ? 'selected' : '' }}>Cook Islands</option>
                    <option value="Costa Rica" {{ $userData->Country == 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
                    <option value="Cote d'Ivoire" {{ $userData->Country == "Cote d'Ivoire" ? 'selected' : '' }}>Cote d'Ivoire</option>
                    <option value="Croatia" {{ $userData->Country == 'Croatia' ? 'selected' : '' }}>Croatia</option>
                    <option value="Cuba" {{ $userData->Country == 'Cuba' ? 'selected' : '' }}>Cuba</option>
                    <option value="Curacao" {{ $userData->Country == 'Curacao' ? 'selected' : '' }}>Curacao</option>
                    <option value="Cyprus" {{ $userData->Country == 'Cyprus' ? 'selected' : '' }}>Cyprus</option>
                    <option value="Czech Republic" {{ $userData->Country == 'Czech Republic' ? 'selected' : '' }}>Czech Republic</option>
                    <option value="Denmark" {{ $userData->Country == 'Denmark' ? 'selected' : '' }}>Denmark</option>
                    <option value="Djibouti" {{ $userData->Country == 'Djibouti' ? 'selected' : '' }}>Djibouti</option>
                    <option value="Dominica" {{ $userData->Country == 'Dominica' ? 'selected' : '' }}>Dominica</option>
                    <option value="Dominican Republic" {{ $userData->Country == 'Dominican Republic' ? 'selected' : '' }}>Dominican Republic</option>
                    <option value="Ecuador" {{ $userData->Country == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                    <option value="Egypt" {{ $userData->Country == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                    <option value="El Salvador" {{ $userData->Country == 'El Salvador' ? 'selected' : '' }}>El Salvador</option>
                    <option value="Equatorial Guinea" {{ $userData->Country == 'Equatorial Guinea' ? 'selected' : '' }}>Equatorial Guinea</option>
                    <option value="Eritrea" {{ $userData->Country == 'Eritrea' ? 'selected' : '' }}>Eritrea</option>
                    <option value="Estonia" {{ $userData->Country == 'Estonia' ? 'selected' : '' }}>Estonia</option>
                    <option value="Ethiopia" {{ $userData->Country == 'Ethiopia' ? 'selected' : '' }}>Ethiopia</option>
                    <option value="Falkland Islands (Malvinas)" {{ $userData->Country == 'Falkland Islands (Malvinas)' ? 'selected' : '' }}>Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands" {{ $userData->Country == 'Faroe Islands' ? 'selected' : '' }}>Faroe Islands</option>
                    <option value="Fiji" {{ $userData->Country == 'Fiji' ? 'selected' : '' }}>Fiji</option>
                    <option value="Finland" {{ $userData->Country == 'Finland' ? 'selected' : '' }}>Finland</option>
                    <option value="France" {{ $userData->Country == 'France' ? 'selected' : '' }}>France</option>
                    <option value="French Guiana" {{ $userData->Country == 'French Guiana' ? 'selected' : '' }}>French Guiana</option>
                    <option value="French Polynesia" {{ $userData->Country == 'French Polynesia' ? 'selected' : '' }}>French Polynesia</option>
                    <option value="French Southern Territories" {{ $userData->Country == 'French Southern Territories' ? 'selected' : '' }}>French Southern Territories</option>
                    <option value="Gabon" {{ $userData->Country == 'Gabon' ? 'selected' : '' }}>Gabon</option>
                    <option value="Gambia" {{ $userData->Country == 'Gambia' ? 'selected' : '' }}>Gambia</option>
                    <option value="Georgia" {{ $userData->Country == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                    <option value="Germany" {{ $userData->Country == 'Germany' ? 'selected' : '' }}>Germany</option>
                    <option value="Ghana" {{ $userData->Country == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                    <option value="Gibraltar" {{ $userData->Country == 'Gibraltar' ? 'selected' : '' }}>Gibraltar</option>
                    <option value="Greece" {{ $userData->Country == 'Greece' ? 'selected' : '' }}>Greece</option>
                    <option value="Greenland" {{ $userData->Country == 'Greenland' ? 'selected' : '' }}>Greenland</option>
                    <option value="Grenada" {{ $userData->Country == 'Grenada' ? 'selected' : '' }}>Grenada</option>
                    <option value="Guadeloupe" {{ $userData->Country == 'Guadeloupe' ? 'selected' : '' }}>Guadeloupe</option>
                    <option value="Guam" {{ $userData->Country == 'Guam' ? 'selected' : '' }}>Guam</option>
                    <option value="Guatemala" {{ $userData->Country == 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                    <option value="Guernsey" {{ $userData->Country == 'Guernsey' ? 'selected' : '' }}>Guernsey</option>
                    <option value="Guinea" {{ $userData->Country == 'Guinea' ? 'selected' : '' }}>Guinea</option>
                    <option value="Guinea-Bissau" {{ $userData->Country == 'Guinea-Bissau' ? 'selected' : '' }}>Guinea-Bissau</option>
                    <option value="Guyana" {{ $userData->Country == 'Guyana' ? 'selected' : '' }}>Guyana</option>
                    <option value="Haiti" {{ $userData->Country == 'Haiti' ? 'selected' : '' }}>Haiti</option>
                    <option value="Heard Island and McDonald Islands" {{ $userData->Country == 'Heard Island and McDonald Islands' ? 'selected' : '' }}>Heard Island and McDonald Islands</option>
                    <option value="Holy See (Vatican City State)" {{ $userData->Country == 'Holy See (Vatican City State)' ? 'selected' : '' }}>Holy See (Vatican City State)</option>
                    <option value="Honduras" {{ $userData->Country == 'Honduras' ? 'selected' : '' }}>Honduras</option>
                    <option value="Hong Kong" {{ $userData->Country == 'Hong Kong' ? 'selected' : '' }}>Hong Kong</option>
                    <option value="Hungary" {{ $userData->Country == 'Hungary' ? 'selected' : '' }}>Hungary</option>
                    <option value="Iceland" {{ $userData->Country == 'Iceland' ? 'selected' : '' }}>Iceland</option>
                    <option value="India" {{ $userData->Country == 'India' ? 'selected' : '' }}>India</option>
                    <option value="Indonesia" {{ $userData->Country == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                    <option value="Iran, Islamic Republic of" {{ $userData->Country == 'Iran, Islamic Republic of' ? 'selected' : '' }}>Iran, Islamic Republic of</option>
                    <option value="Iraq" {{ $userData->Country == 'Iraq' ? 'selected' : '' }}>Iraq</option>
                    <option value="Ireland" {{ $userData->Country == 'Ireland' ? 'selected' : '' }}>Ireland</option>
                    <option value="Isle of Man" {{ $userData->Country == 'Isle of Man' ? 'selected' : '' }}>Isle of Man</option>
                    <option value="Israel" {{ $userData->Country == 'Israel' ? 'selected' : '' }}>Israel</option>
                    <option value="Italy" {{ $userData->Country == 'Italy' ? 'selected' : '' }}>Italy</option>
                    <option value="Jamaica" {{ $userData->Country == 'Jamaica' ? 'selected' : '' }}>Jamaica</option>
                    <option value="Japan" {{ $userData->Country == 'Japan' ? 'selected' : '' }}>Japan</option>
                    <option value="Jersey" {{ $userData->Country == 'Jersey' ? 'selected' : '' }}>Jersey</option>
                    <option value="Jordan" {{ $userData->Country == 'Jordan' ? 'selected' : '' }}>Jordan</option>
                    <option value="Kazakhstan" {{ $userData->Country == 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                    <option value="Kenya" {{ $userData->Country == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                    <option value="Kiribati" {{ $userData->Country == 'Kiribati' ? 'selected' : '' }}>Kiribati</option>
                    <option value="Korea, Democratic People's Republic of" {{ $userData->Country == 'Korea, Democratic People\'s Republic of' ? 'selected' : '' }}>Korea, Democratic People's Republic of</option>
                    <option value="Korea, Republic of" {{ $userData->Country == 'Korea, Republic of' ? 'selected' : '' }}>Korea, Republic of</option>
                    <option value="Kosovo" {{ $userData->Country == 'Kosovo' ? 'selected' : '' }}>Kosovo</option>
                    <option value="Kuwait" {{ $userData->Country == 'Kuwait' ? 'selected' : '' }}>Kuwait</option>
                    <option value="Kyrgyzstan" {{ $userData->Country == 'Kyrgyzstan' ? 'selected' : '' }}>Kyrgyzstan</option>
                    <option value="Lao People's Democratic Republic" {{ $userData->Country == 'Lao People\'s Democratic Republic' ? 'selected' : '' }}>Lao People's Democratic Republic</option>
                    <option value="Latvia" {{ $userData->Country == 'Latvia' ? 'selected' : '' }}>Latvia</option>
                    <option value="Lebanon" {{ $userData->Country == 'Lebanon' ? 'selected' : '' }}>Lebanon</option>
                    <option value="Lesotho" {{ $userData->Country == 'Lesotho' ? 'selected' : '' }}>Lesotho</option>
                    <option value="Liberia" {{ $userData->Country == 'Liberia' ? 'selected' : '' }}>Liberia</option>
                    <option value="Libyan Arab Jamahiriya" {{ $userData->Country == 'Libyan Arab Jamahiriya' ? 'selected' : '' }}>Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein" {{ $userData->Country == 'Liechtenstein' ? 'selected' : '' }}>Liechtenstein</option>
                    <option value="Lithuania" {{ $userData->Country == 'Lithuania' ? 'selected' : '' }}>Lithuania</option>
                    <option value="Luxembourg" {{ $userData->Country == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                    <option value="Macao" {{ $userData->Country == 'Macao' ? 'selected' : '' }}>Macao</option>
                    <option value="Macedonia, the Former Yugoslav Republic of" {{ $userData->Country == 'Macedonia, the Former Yugoslav Republic of' ? 'selected' : '' }}>Macedonia, the Former Yugoslav Republic of</option>
                    <option value="Madagascar" {{ $userData->Country == 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
                    <option value="Malawi" {{ $userData->Country == 'Malawi' ? 'selected' : '' }}>Malawi</option>
                    <option value="Malaysia" {{ $userData->Country == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                    <option value="Maldives" {{ $userData->Country == 'Maldives' ? 'selected' : '' }}>Maldives</option>
                    <option value="Mali" {{ $userData->Country == 'Mali' ? 'selected' : '' }}>Mali</option>
                    <option value="Malta" {{ $userData->Country == 'Malta' ? 'selected' : '' }}>Malta</option>
                    <option value="Marshall Islands" {{ $userData->Country == 'Marshall Islands' ? 'selected' : '' }}>Marshall Islands</option>
                    <option value="Martinique" {{ $userData->Country == 'Martinique' ? 'selected' : '' }}>Martinique</option>
                    <option value="Mauritania" {{ $userData->Country == 'Mauritania' ? 'selected' : '' }}>Mauritania</option>
                    <option value="Mauritius" {{ $userData->Country == 'Mauritius' ? 'selected' : '' }}>Mauritius</option>
                    <option value="Mayotte" {{ $userData->Country == 'Mayotte' ? 'selected' : '' }}>Mayotte</option>
                    <option value="Mexico" {{ $userData->Country == 'Mexico' ? 'selected' : '' }}>Mexico</option>
                    <option value="Micronesia, Federated States of" {{ $userData->Country == 'Micronesia, Federated States of' ? 'selected' : '' }}>Micronesia, Federated States of</option>
                    <option value="Moldova, Republic of" {{ $userData->Country == 'Moldova, Republic of' ? 'selected' : '' }}>Moldova, Republic of</option>
                    <option value="Monaco" {{ $userData->Country == 'Monaco' ? 'selected' : '' }}>Monaco</option>
                    <option value="Mongolia" {{ $userData->Country == 'Mongolia' ? 'selected' : '' }}>Mongolia</option>
                    <option value="Montenegro" {{ $userData->Country == 'Montenegro' ? 'selected' : '' }}>Montenegro</option>
                    <option value="Montserrat" {{ $userData->Country == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                    <option value="Morocco" {{ $userData->Country == 'Morocco' ? 'selected' : '' }}>Morocco</option>
                    <option value="Mozambique" {{ $userData->Country == 'Mozambique' ? 'selected' : '' }}>Mozambique</option>
                    <option value="Myanmar" {{ $userData->Country == 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                    <option value="Namibia" {{ $userData->Country == 'Namibia' ? 'selected' : '' }}>Namibia</option>
                    <option value="Nauru" {{ $userData->Country == 'Nauru' ? 'selected' : '' }}>Nauru</option>
                    <option value="Nepal" {{ $userData->Country == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                    <option value="Netherlands" {{ $userData->Country == 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                    <option value="Netherlands Antilles" {{ $userData->Country == 'Netherlands Antilles' ? 'selected' : '' }}>Netherlands Antilles</option>
                    <option value="New Caledonia" {{ $userData->Country == 'New Caledonia' ? 'selected' : '' }}>New Caledonia</option>
                    <option value="New Zealand" {{ $userData->Country == 'New Zealand' ? 'selected' : '' }}>New Zealand</option>
                    <option value="Nicaragua" {{ $userData->Country == 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
                    <option value="Niger" {{ $userData->Country == 'Niger' ? 'selected' : '' }}>Niger</option>
                    <option value="Nigeria" {{ $userData->Country == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                    <option value="Niue" {{ $userData->Country == 'Niue' ? 'selected' : '' }}>Niue</option>
                    <option value="Norfolk Island" {{ $userData->Country == 'Norfolk Island' ? 'selected' : '' }}>Norfolk Island</option>
                    <option value="Northern Mariana Islands" {{ $userData->Country == 'Northern Mariana Islands' ? 'selected' : '' }}>Northern Mariana Islands</option>
                    <option value="Norway" {{ $userData->Country == 'Norway' ? 'selected' : '' }}>Norway</option>
                    <option value="Oman" {{ $userData->Country == 'Oman' ? 'selected' : '' }}>Oman</option>
                    <option value="Pakistan" {{ $userData->Country == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                    <option value="Palau" {{ $userData->Country == 'Palau' ? 'selected' : '' }}>Palau</option>
                    <option value="Palestinian Territory, Occupied" {{ $userData->Country == 'Palestinian Territory, Occupied' ? 'selected' : '' }}>Palestinian Territory, Occupied</option>
                    <option value="Panama" {{ $userData->Country == 'Panama' ? 'selected' : '' }}>Panama</option>
                    <option value="Papua New Guinea" {{ $userData->Country == 'Papua New Guinea' ? 'selected' : '' }}>Papua New Guinea</option>
                    <option value="Paraguay" {{ $userData->Country == 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                    <option value="Peru" {{ $userData->Country == 'Peru' ? 'selected' : '' }}>Peru</option>
                    <option value="Philippines" {{ $userData->Country == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                    <option value="Pitcairn" {{ $userData->Country == 'Pitcairn' ? 'selected' : '' }}>Pitcairn</option>
                    <option value="Poland" {{ $userData->Country == 'Poland' ? 'selected' : '' }}>Poland</option>
                    <option value="Portugal" {{ $userData->Country == 'Portugal' ? 'selected' : '' }}>Portugal</option>
                    <option value="Puerto Rico" {{ $userData->Country == 'Puerto Rico' ? 'selected' : '' }}>Puerto Rico</option>
                    <option value="Qatar" {{ $userData->Country == 'Qatar' ? 'selected' : '' }}>Qatar</option>
                    <option value="Reunion" {{ $userData->Country == 'Reunion' ? 'selected' : '' }}>Reunion</option>
                    <option value="Romania" {{ $userData->Country == 'Romania' ? 'selected' : '' }}>Romania</option>
                    <option value="Russian Federation" {{ $userData->Country == 'Russian Federation' ? 'selected' : '' }}>Russian Federation</option>
                    <option value="Rwanda" {{ $userData->Country == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                    <option value="Saint Barthelemy" {{ $userData->Country == 'Saint Barthelemy' ? 'selected' : '' }}>Saint Barthelemy</option>
                    <option value="Saint Helena" {{ $userData->Country == 'Saint Helena' ? 'selected' : '' }}>Saint Helena</option>
                    <option value="Saint Kitts and Nevis" {{ $userData->Country == 'Saint Kitts and Nevis' ? 'selected' : '' }}>Saint Kitts and Nevis</option>
                    <option value="Saint Lucia" {{ $userData->Country == 'Saint Lucia' ? 'selected' : '' }}>Saint Lucia</option>
                    <option value="Saint Martin" {{ $userData->Country == 'Saint Martin' ? 'selected' : '' }}>Saint Martin</option>
                    <option value="Saint Pierre and Miquelon" {{ $userData->Country == 'Saint Pierre and Miquelon' ? 'selected' : '' }}>Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent and the Grenadines" {{ $userData->Country == 'Saint Vincent and the Grenadines' ? 'selected' : '' }}>Saint Vincent and the Grenadines</option>
                    <option value="Samoa" {{ $userData->Country == 'Samoa' ? 'selected' : '' }}>Samoa</option>
                    <option value="San Marino" {{ $userData->Country == 'San Marino' ? 'selected' : '' }}>San Marino</option>
                    <option value="Sao Tome and Principe" {{ $userData->Country == 'Sao Tome and Principe' ? 'selected' : '' }}>Sao Tome and Principe</option>
                    <option value="Saudi Arabia" {{ $userData->Country == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                    <option value="Senegal" {{ $userData->Country == 'Senegal' ? 'selected' : '' }}>Senegal</option>
                    <option value="Serbia" {{ $userData->Country == 'Serbia' ? 'selected' : '' }}>Serbia</option>
                    <option value="Serbia and Montenegro" {{ $userData->Country == 'Serbia and Montenegro' ? 'selected' : '' }}>Serbia and Montenegro</option>
                    <option value="Seychelles" {{ $userData->Country == 'Seychelles' ? 'selected' : '' }}>Seychelles</option>
                    <option value="Sierra Leone" {{ $userData->Country == 'Sierra Leone' ? 'selected' : '' }}>Sierra Leone</option>
                    <option value="Singapore" {{ $userData->Country == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                    <option value="Sint Maarten" {{ $userData->Country == 'Sint Maarten' ? 'selected' : '' }}>Sint Maarten</option>
                    <option value="Slovakia" {{ $userData->Country == 'Slovakia' ? 'selected' : '' }}>Slovakia</option>
                    <option value="Slovenia" {{ $userData->Country == 'Slovenia' ? 'selected' : '' }}>Slovenia</option>
                    <option value="Solomon Islands" {{ $userData->Country == 'Solomon Islands' ? 'selected' : '' }}>Solomon Islands</option>
                    <option value="Somalia" {{ $userData->Country == 'Somalia' ? 'selected' : '' }}>Somalia</option>
                    <option value="South Africa" {{ $userData->Country == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                    <option value="South Georgia and the South Sandwich Islands" {{ $userData->Country == 'South Georgia and the South Sandwich Islands' ? 'selected' : '' }}>South Georgia and the South Sandwich Islands</option>
                    <option value="South Sudan" {{ $userData->Country == 'South Sudan' ? 'selected' : '' }}>South Sudan</option>
                    <option value="Spain" {{ $userData->Country == 'Spain' ? 'selected' : '' }}>Spain</option>
                    <option value="Sri Lanka" {{ $userData->Country == 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                    <option value="Sudan" {{ $userData->Country == 'Sudan' ? 'selected' : '' }}>Sudan</option>
                    <option value="Suriname" {{ $userData->Country == 'Suriname' ? 'selected' : '' }}>Suriname</option>
                    <option value="Svalbard and Jan Mayen" {{ $userData->Country == 'Svalbard and Jan Mayen' ? 'selected' : '' }}>Svalbard and Jan Mayen</option>
                    <option value="Swaziland" {{ $userData->Country == 'Swaziland' ? 'selected' : '' }}>Swaziland</option>
                    <option value="Sweden" {{ $userData->Country == 'Sweden' ? 'selected' : '' }}>Sweden</option>
                    <option value="Switzerland" {{ $userData->Country == 'Switzerland' ? 'selected' : '' }}>Switzerland</option>
                    <option value="Syrian Arab Republic" {{ $userData->Country == 'Syrian Arab Republic' ? 'selected' : '' }}>Syrian Arab Republic</option>
                    <option value="Taiwan, Province of China" {{ $userData->Country == 'Taiwan, Province of China' ? 'selected' : '' }}>Taiwan, Province of China</option>
                    <option value="Tajikistan" {{ $userData->Country == 'Tajikistan' ? 'selected' : '' }}>Tajikistan</option>
                    <option value="Tanzania, United Republic of" {{ $userData->Country == 'Tanzania, United Republic of' ? 'selected' : '' }}>Tanzania, United Republic of</option>
                    <option value="Thailand" {{ $userData->Country == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                    <option value="Timor-Leste" {{ $userData->Country == 'Timor-Leste' ? 'selected' : '' }}>Timor-Leste</option>
                    <option value="Togo" {{ $userData->Country == 'Togo' ? 'selected' : '' }}>Togo</option>
                    <option value="Tokelau" {{ $userData->Country == 'Tokelau' ? 'selected' : '' }}>Tokelau</option>
                    <option value="Tonga" {{ $userData->Country == 'Tonga' ? 'selected' : '' }}>Tonga</option>
                    <option value="Trinidad and Tobago" {{ $userData->Country == 'Trinidad and Tobago' ? 'selected' : '' }}>Trinidad and Tobago</option>
                    <option value="Tunisia" {{ $userData->Country == 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
                    <option value="Turkey" {{ $userData->Country == 'Turkey' ? 'selected' : '' }}>Turkey</option>
                    <option value="Turkmenistan" {{ $userData->Country == 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
                    <option value="Turks and Caicos Islands" {{ $userData->Country == 'Turks and Caicos Islands' ? 'selected' : '' }}>Turks and Caicos Islands</option>
                    <option value="Tuvalu" {{ $userData->Country == 'Tuvalu' ? 'selected' : '' }}>Tuvalu</option>
                    <option value="Uganda" {{ $userData->Country == 'Uganda' ? 'selected' : '' }}>Uganda</option>
                    <option value="Ukraine" {{ $userData->Country == 'Ukraine' ? 'selected' : '' }}>Ukraine</option>
                    <option value="United Arab Emirates" {{ $userData->Country == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                    <option value="United Kingdom" {{ $userData->Country == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                    <option value="United States" {{ $userData->Country == 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="United States Minor Outlying Islands" {{ $userData->Country == 'United States Minor Outlying Islands' ? 'selected' : '' }}>United States Minor Outlying Islands</option>
                    <option value="Uruguay" {{ $userData->Country == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                    <option value="Uzbekistan" {{ $userData->Country == 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
                    <option value="Vanuatu" {{ $userData->Country == 'Vanuatu' ? 'selected' : '' }}>Vanuatu</option>
                    <option value="Venezuela" {{ $userData->Country == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                    <option value="Viet Nam" {{ $userData->Country == 'Viet Nam' ? 'selected' : '' }}>Viet Nam</option>
                    <option value="Virgin Islands, British" {{ $userData->Country == 'Virgin Islands, British' ? 'selected' : '' }}>Virgin Islands, British</option>
                    <option value="Virgin Islands, U.S." {{ $userData->Country == 'Virgin Islands, U.S.' ? 'selected' : '' }}>Virgin Islands, U.S.</option>
                    <option value="Wallis and Futuna" {{ $userData->Country == 'Wallis and Futuna' ? 'selected' : '' }}>Wallis and Futuna</option>
                    <option value="Western Sahara" {{ $userData->Country == 'Western Sahara' ? 'selected' : '' }}>Western Sahara</option>
                    <option value="Yemen" {{ $userData->Country == 'Yemen' ? 'selected' : '' }}>Yemen</option>
                    <option value="Zambia" {{ $userData->Country == 'Zambia' ? 'selected' : '' }}>Zambia</option>
                    <option value="Zimbabwe" {{ $userData->Country == 'Zimbabwe' ? 'selected' : '' }}>Zimbabwe</option>

                    <!-- Add more options for other countries here -->
                </select>
            </div>

            <div class="mb-3">
                <label for="state" class="form-label">State:</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ $userData->State ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City:</label>
                <input type="text" class="form-control" id="city" name="city" value="{{ $userData->City ?? '' }}" required>
            </div>
            <button class="btn btn-primary">Update</button>
    </form>

    </div>

    <script>
        function displayImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                var imagePreview = document.getElementById("image-preview");
                imagePreview.querySelector("img").src = e.target.result;
                imagePreview.classList.remove("d-none");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            var imagePreview = document.getElementById("image-preview");
            imagePreview.classList.add("d-none");
            document.getElementById("avatar").value = "";
        }
    </script>
    
</x-layout>

