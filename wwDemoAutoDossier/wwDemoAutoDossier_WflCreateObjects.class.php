<?php
/****************************************************************************
   Copyright 2016 WoodWing Software BV

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
****************************************************************************/

require_once BASEDIR . '/server/interfaces/services/wfl/WflCreateObjects_EnterpriseConnector.class.php';

class wwDemoAutoDossier_WflCreateObjects extends WflCreateObjects_EnterpriseConnector
{
	final public function getPrio()     { return self::PRIO_DEFAULT; }
	final public function getRunMode()  { return self::RUNMODE_BEFORE; }

//
//
//  Check if new Article or Layout already is contained by a Dossier
//  If not, create Dossier on the fly
//
//
	final public function runBefore( WflCreateObjectsRequest &$req )
	{
		LogHandler::Log( 'wwDemoAutoDossier', 'DEBUG', 'Called: wwDemoAutoDossier_WflCreateObjects->runBefore()' );
		require_once dirname(__FILE__) . '/config.php';
		
		foreach ($req->Objects as $object) {
		    if ($object->MetaData->BasicMetaData->Type ==  'Layout' || $object->MetaData->BasicMetaData->Type ==  'Article') {
                if ($object->Relations) {
					$createdossier = true;
					foreach ($object->Relations as $relation) {
						if ($relation->Type == 'Contained') {
							$createdossier = false;
							break;
						}
					}
					if ($createdossier) {
						$r = new Relation();
						$r->Type = 'Contained';
						$r->Parent = -1;
						$object->Relations[] = $r;
					}
				}                
		    }		
		}
		// TODO: Add your code that hooks into the service request.
		// NOTE: Replace RUNMODE_BEFOREAFTER with RUNMODE_AFTER when this hook is not needed.

		LogHandler::Log( 'wwDemoAutoDossier', 'DEBUG', 'Returns: wwDemoAutoDossier_WflCreateObjects->runBefore()' );
	} 

	final public function runAfter( WflCreateObjectsRequest $req, WflCreateObjectsResponse &$resp )
	{
	} 
	
	final public function onError( WflCreateObjectsRequest $req, BizException $e )
	{
	} 
	
	// Not called.
	final public function runOverruled( WflCreateObjectsRequest $req )
	{
		$req = $req; // keep code analyzer happy
	} 
}
