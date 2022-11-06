<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;

class NetworkSettingsPacket extends DataPacket/* implements ClientboundPacket*/
{
	public const NETWORK_ID = ProtocolInfo::NETWORK_SETTINGS_PACKET;

	public const COMPRESS_NOTHING = 0;
	public const COMPRESS_EVERYTHING = 1;

	/** @var int */
	public $compressionThreshold;
	public int $compressionAlgorithm;
	public bool $enableClientThrottling;
	public int $clientThrottleThreshold;
	public float $clientThrottleScalar;

	public static function create(int $compressionThreshold):self{
		$result = new self;
		$result->compressionThreshold = $compressionThreshold;
		return $result;
	}

	public function getCompressionThreshold():int{
		return $this->compressionThreshold;
	}

	public function getCompressionAlgorithm():int{
		return $this->compressionAlgorithm;
	}

	public function isEnableClientThrottling():bool{
		return $this->enableClientThrottling;
	}

	public function getClientThrottleThreshold():int{
		return $this->clientThrottleThreshold;
	}

	public function getClientThrottleScalar():float{
		return $this->clientThrottleScalar;
	}

	public function canBeSentBeforeLogin():bool{
		return true;
	}

	protected function decodePayload():void{
		$this->compressionThreshold = $this->getLShort();
		$this->compressionAlgorithm = $this->getLShort();
		$this->enableClientThrottling = $this->getBool();
		$this->clientThrottleThreshold = $this->getByte();
		$this->clientThrottleScalar = $this->getLFloat();
	}

	protected function encodePayload():void{
		$this->putLShort($this->compressionThreshold);
		$this->putLShort($this->compressionAlgorithm);
		$this->putBool($this->enableClientThrottling);
		$this->putByte($this->clientThrottleThreshold);
		$this->putLFloat($this->clientThrottleScalar);
	}

	public function handle(NetworkSession $handler):bool{
		return $handler->handleNetworkSettings($this);
	}
}
